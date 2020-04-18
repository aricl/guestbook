<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
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
     * @ORM\Column(type="integer")
     */
    private int $year;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $international;
    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Comment",
     *     mappedBy="conference",
     *     orphanRemoval=true
     * )
     */
    private ?PersistentCollection $comments;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(string $city, bool $international, int $year)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->city = $city;
        $this->createdAt = new DateTimeImmutable();
        $this->international = $international;
        $this->year = $year;
    }

    public function __toString(): string
    {
        return $this->city . ' ' . $this->year;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return bool
     */
    public function isInternational(): bool
    {
        return $this->international;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments ? $this->comments->toArray() : [];
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
