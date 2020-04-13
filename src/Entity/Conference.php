<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="string", nullable=true)
     */
    private string $city;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private bool $international;
    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Comment",
     *     mappedBy="conference",
     *     orphanRemoval=true
     * )
     */
    private ArrayCollection $comments;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new DateTimeImmutable();
        $this->comments = new ArrayCollection();
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function isInternational(): ?bool
    {
        return $this->international;
    }

    public function setInternational(?bool $international): void
    {
        $this->international = $international;
    }
}
