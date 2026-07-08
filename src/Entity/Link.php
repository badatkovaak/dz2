<?php
namespace App\Entity;

use App\Enum\LinkExpirationType as LEType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

#[ORM\Entity]
class Link
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $shortUrl;

    #[Assert\Url]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private string $longUrl;

    #[ORM\Column]
    private ?\DateTime $creationTime = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $lastUseTime = null;

    #[ORM\Column]
    private int $useCount = 0;

    #[ORM\ManyToOne(inversedBy: 'links')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[Assert\Type(DateTime::class)]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $expiryDate = null;

    #[ORM\Column(enumType: LEType::class)]
    private ?LEType $expirationType = null;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getExpiryDate(): ?\DateTime
    {
        return $this->expiryDate;
    }

    public function setExpiryDate(?\DateTime $expiryDate): static
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    public function expiryDateToString(): string
    {
        return is_null($this->expiryDate) ? '' : $this->expiryDate->format('Y-m-d H:i:s');
    }

    public function getExpirationType(): ?LEType
    {
        return $this->expirationType;
    }

    public function setExpirationType(LEType $expirationType): static
    {
        $this->expirationType = $expirationType;

        return $this;
    }

    public function expirationTypeToString(): string
    {
        return $this->expirationType->ToString();
    }
}
