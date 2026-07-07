<?php

namespace App\Repository;

use App\Entity\Link;
use App\Entity\User;
use App\Enum\LinkExpirationType as LEType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTime;

/**
 * @extends ServiceEntityRepository<Link>
 */
class LinkRepository extends ServiceEntityRepository
{
    public const SHORT_URL_LEN = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    public function getAllLinks(): array
    {
        return $this->findAll();
    }

    public function getLinkByUrl(string $shortUrl): ?Link
    {
        return $this->findOneBy(['shortUrl' => $shortUrl]);
    }

    public function getLinkById(int $id): ?Link
    {
        return $this->find($id);
    }

    public function save(Link $link): Link
    {
        $em = $this->getEntityManager();
        $em->persist($link);
        $em->flush();
        return $link;
    }

    public function deleteLink(Link $link): void
    {
        $link->getOwner()->removeLink($link);
        $em = $this->getEntityManager();
        $em->remove($link);
        $em->flush();
    }

    public function deleteLinkById(int $id): bool
    {
        $link = $this->find($id);

        if (is_null($link)) {
            return false;
        }

        $this->deleteLink($link);
        return true;
    }

    public function create(string $longUrl, User $user, LEType $type, ValidatorInterface $val, ?DateTime $date = null): ?Link
    {
        $link = new Link();

        if (filter_var($longUrl, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        $link->setLongUrl($longUrl);
        $link->setShortUrl($this->generateShortUrl());
        $link->setCreationTime(date_create());
        $link->setOwner($user);
        $user->addLink($link);
        $link->setExpirationType($type);

        if ($type === LEType::ExpireByDate) {
            if (is_null($date)) {
                return null;
            }

            $link->setExpiryDate($date);
        }

        $val->validate($link);

        return $link;
    }

    public function fromJson(string $input, User $user, ValidatorInterface $val): ?Link
    {
        if (!json_validate($input)) {
            return null;
        }

        $obj = json_decode($input, true);
        $longUrl = array_key_exists('longUrl', $obj) ? $obj['longUrl'] : null;

        if (is_null($longUrl)) {
            return null;
        }

        $expirationTypeStr = array_key_exists('type', $obj) ? $obj['type'] : null;

        if (is_null($expirationTypeStr)) {
            return null;
        }

        $type = LEType::fromString($expirationTypeStr);

        $date = null;

        if ($type === LEType::ExpireByDate) {
            $dateString = array_key_exists('date', $obj) ? $obj['date'] : null;

            if (is_null($dateString)) {
                return null;
            }

            $date = date_create_from_format('Y-m-d\TH:i', $dateString);

            if (!$date) {
                return null;
            }
        }

        return $this->create($longUrl, $user, $type, $date, $val);
    }

    public function updateTimeAndUsage(Link $link): void
    {
        $link->incrementUseCount();
        $link->updateLastUseTime();
        $this->save($link);
    }

    public function updateFromJson(Link $link, string $content): bool
    {
        if (!json_validate($content)) {
            return false;
        }

        $obj = json_decode($content, true);
        $newLongUrl = array_key_exists('longUrl', $obj) ? $obj['longUrl'] : null;
        $newShortUrl = array_key_exists('shortUrl', $obj) ? $obj['shortUrl'] : null;

        if (is_null($newShortUrl) && is_null($newLongUrl)) {
            return true;
        }

        if (!is_null($newLongUrl)) {
            $link->setLongUrl($newLongUrl);
        }

        if (!is_null($newShortUrl)) {
            if (!$this->shortUrlIsUnique($newShortUrl)) {
                return false;
            }

            $link->setShortUrl($newShortUrl);
        }

        $this->save($link);

        return true;
    }

    public static function generateRandomString(int $len): ?string
    {
        if (is_null($len) || $len < 1) {
            return null;
        }

        $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($alphabet) - 1;
        $result = '';

        for ($i = 0; $i < $len; $i++) {
            $result .= $alphabet[random_int(0, $max)];
        }

        return $result;
    }

    public function generateShortUrl(): string
    {
        do {
            $shortUrl = $this->generateRandomString(self::SHORT_URL_LEN);
        } while (!$this->shortUrlIsUnique($shortUrl));
        return $shortUrl;
    }

    public function shortUrlIsUnique(string $shortUrl): bool
    {
        $links = $this->findBy(['shortUrl' => $shortUrl]);
        return count($links) === 0;
    }

    public function finishCreation(Link $link, User $user, ValidatorInterface $val): void
    {
        $link->setShortUrl($this->generateShortUrl());
        $link->setCreationTime(date_create());
        $link->setOwner($user);

        $val->validate($link);
        $user->addLink($link);
    }
}
