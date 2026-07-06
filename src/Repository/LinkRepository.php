<?php

namespace App\Repository;

use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
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
        return $this->findBy($id);
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
        $em = $this->getEntityManager();
        $em->remove($link);
        $em->flush();
    }

    public function create(string $longUrl): ?Link
    {
        $link = new Link();

        if (filter_var($longUrl, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        $link->setLongUrl($longUrl);
        $link->setShortUrl($this->generateShortUrl());
        $link->setCreationTime(date_create());
        return $link;
    }

    public function fromJson(string $input): ?Link
    {
        if (!json_validate($input)) {
            return null;
        }

        $obj = json_decode($input, true);
        $longUrl = array_key_exists('longUrl', $obj) ? $obj['longUrl'] : null;

        if (is_null($longUrl)) {
            return null;
        }

        return $this->create($longUrl);
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
}
