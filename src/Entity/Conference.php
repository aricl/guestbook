<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class Conference
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private string $id;
    /**
     * @ORM\Column(type="string")
     */
    private string $city;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $author;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $text;
    /**
     * @ORM\Column(type="string")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(string $city)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->city = $city;
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getCity(): string
    {
        return $this->city;
    }
    public function getAuthor(): ?string
    {
        return $this->author;
    }
    public function getText(): ?string
    {
        return $this->text;
    }
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
