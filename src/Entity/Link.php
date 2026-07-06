<?php
namespace App\Entity;

use Doctrine\ORM\EntityManagerInterface as EMInterface;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity]
class Link
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $shortUrl;

    #[ORM\Column(length: 255)]
    private string $longUrl;

    #[ORM\Column]
    private ?\DateTime $creationTime = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $lastUseTime = null;

    #[ORM\Column]
    private int $useCount = 0;

    public const SHORT_URL_LEN = 5;

    public function getId(): int
    {
        return $this->id;
    }

    public function getShortUrl(): string
    {
        return $this->shortUrl;
    }

    public function setShortUrl(string $shortUrl): static
    {
        $this->shortUrl = $shortUrl;

        return $this;
    }

    public function setLongUrl(string $longUrl): static
    {
        $this->longUrl = $longUrl;

        return $this;
    }

    public function getLongUrl(): string
    {
        return $this->longUrl;
    }

    public function getCreationTime(): ?\DateTime
    {
        return $this->creationTime;
    }

    public function setCreationTime(\DateTime $creationTime): static
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    public function getLastUseTime(): ?\DateTime
    {
        return $this->lastUseTime;
    }

    public function setLastUsedTime(?\DateTime $lastUseTime): static
    {
        $this->lastUseTime = $lastUseTime;

        return $this;
    }

    public function getUseCount(): int
    {
        return $this->useCount;
    }

    public function setUseCount(int $useCount): static
    {
        $this->useCount = $useCount;

        return $this;
    }

    public function incrementUseCount(): void
    {
        $this->useCount++;
    }

    public function updateLastUseTime(): void
    {
        $this->lastUseTime = date_create();
    }

    public static function create(string $longUrl, EMInterface $em): ?Link
    {
        $link = new Link();

        if (filter_var($longUrl, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        $link->setLongUrl($longUrl);
        $link->setShortUrl(Link::generateShortUrl($em));
        $link->setCreationTime(date_create());
        return $link;
    }

    public static function tryCreate(?string $longUrl, EMInterface $em): ?Link
    {
        if (is_null($longUrl)) {
            return null;
        }

        return Link::create($longUrl, $em);
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

    public static function generateShortUrl(EMInterface $em): string
    {
        do {
            $shortUrl = Link::generateRandomString(self::SHORT_URL_LEN);
        } while (!Link::shortUrlIsUnique($shortUrl, $em));
        return $shortUrl;
    }

    public static function createLink(string $longUrl, EMInterface $em): Link
    {
        $link = Link::create($longUrl, $em);
        $em->flush();
        return $link;
    }

    public static function saveLink(Link $link, EMInterface $em): void
    {
        $em->persist($link);
        $em->flush();
    }

    /* public static function getLinkById(int $id, EMInterface $em): ?Link */
    /* { */
    /* $link = $em->getRepository(Link::class)->find($id); */
    /**/
    /* if (!$link) { */
    /* return null; */
    /* } */
    /**/
    /* return $link; */
    /* } */
    /**/
    /* public static function getLinkByUrl(string $shortUrl, EMInterface $em): ?Link */
    /* { */
    /* $link = $em->getRepository(Link::class)->findOneBy(['shortUrl' => $shortUrl]); */
    /**/
    /* if (!$link) { */
    /* return null; */
    /* } */
    /**/
    /* return $link; */
    /* } */
    /**/
    /* public static function getAllLinks(EMInterface $em): array */
    /* { */
    /* return $em->getRepository(Link::class)->findAll(); */
    /* } */

    /* public static function deleteLink(int $id, EMInterface $em): bool */
    /* { */
    /* $link = $em->getRepository(Link::class)->find($id); */
    /**/
    /* if (!$link) { */
    /* return false; */
    /* } */
    /**/
    /* $em->remove($link); */
    /* $em->flush(); */
    /* return true; */
    /* } */

    public static function updateTimeAndUsageById(int $id, EMInterface $em): ?Link
    {
        $link = $em->getRepository(Link::class)->find($id);

        if (!$link) {
            return null;
        }

        return Link::updateTimeAndUsage($link, $em);
    }

    public static function updateTimeAndUsage(Link $link, EMInterface $em): Link
    {
        $link->setUseCount($link->getUseCount() + 1);
        $link->setLastUsedTime(date_create());
        $em->persist($link);
        $em->flush();
        return $link;
    }

    public static function fromJson(string $input, EMInterface $em): ?Link
    {
        if (!json_validate($input)) {
            return null;
        }

        $obj = json_decode($input, true);
        $longUrl = array_key_exists('longUrl', $obj) ? $obj['longUrl'] : null;
        return Link::tryCreate($longUrl, $em);
    }

    public static function shortUrlIsUnique(string $shortUrl, EMInterface $em): bool
    {
        $links = $em->getRepository(Link::class)->findBy(['shortUrl' => $shortUrl]);

        return count($links) === 0;
    }

    public function updateFromJson(string $content, EMInterface $em): bool
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
            $this->setLongUrl($newLongUrl);
        }

        if (!is_null($newShortUrl)) {
            if (!Link::shortUrlIsUnique($newShortUrl, $em)) {
                return false;
            }

            $this->setShortUrl($newShortUrl);
        }

        $em->persist($this);
        $em->flush();

        return true;
    }

    public function creationTimeToString(): string
    {
        return $this->creationTime->format('Y-m-d H:i:s');
    }

    public function lastUseTimeToString(): string
    {
        $time = $this->lastUseTime;
        return is_null($time) ? '' : $time->format('Y-m-d H:i:s');
    }

    public function getShortUrlInProperForm(): string
    {
        return "http://localhost:8000/short/$this->shortUrl";
    }
}
