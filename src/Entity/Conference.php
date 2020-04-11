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
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $international;

    public function __construct(string $city, bool $international)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->city = $city;
        $this->createdAt = new DateTimeImmutable();
        $this->international = $international;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function isInternational(): bool
    {
        return $this->international;
    }
}
