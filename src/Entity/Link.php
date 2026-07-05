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
    private ?\DateTime $lastUsedTime = null;

    #[ORM\Column]
    private int $useCount = 0;

    public const SHORT_URL_LEN = 5;

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

    public function getLastUsedTime(): ?\DateTime
    {
        return $this->lastUsedTime;
    }

    public function setLastUsedTime(?\DateTime $lastUsedTime): static
    {
        $this->lastUsedTime = $lastUsedTime;

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

    public static function create(string $longUrl): Link
    {
        $link = new Link();
        $link->setLongUrl($longUrl);
        $link->setShortUrl(Link::generateShortUrl());
        $link->setCreationTime(date_create());
        return $link;
    }

    public static function tryCreate(?string $longUrl): ?Link
    {
        if (is_null($longUrl)) {
            return null;
        }

        return Link::create($longUrl);
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

    public static function generateShortUrl(): string
    {
        /* define(SHORT_URL_LEN) */
        /* global SHORT_URL_LEN; */
        return Link::generateRandomString(self::SHORT_URL_LEN);
    }

    public static function createLink(string $longUrl, EMInterface $em): Link
    {
        $link = Link::create($longUrl);
        $em->flush();
        return $link;
    }

    public static function saveLink(Link $link, EMInterface $em): void
    {
        $em->persist($link);
        $em->flush();
    }

    public static function getLinkById(int $id, EMInterface $em): ?Link
    {
        $link = $em->getRepository(Link::class)->find($id);

        if (!$link) {
            return null;
        }

        return $link;
    }

    public static function getLinkByUrl(string $shortUrl, EMInterface $em): ?Link
    {
        $link = $em->getRepository(Link::class)->findOneBy(['shortUrl' => $shortUrl]);

        if (!$link) {
            return null;
        }

        return $link;
    }

    public static function getAllLinks(EMInterface $em): array
    {
        return $em->getRepository(Link::class)->findAll();
    }

    public static function deleteLink(int $id, EMInterface $em): bool
    {
        $link = $em->getRepository(Link::class)->find($id);

        if (!$link) {
            return false;
        }

        $em->remove($link);
        $em->flush();
        return true;
    }

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

    public static function fromJson(string $input): ?Link
    {
        if (!json_validate($input)) {
            return null;
        }

        $obj = json_decode($input, true);
        $longUrl = array_key_exists('longUrl', $obj) ? $obj['longUrl'] : null;
        return Link::tryCreate($longUrl);
    }
}
