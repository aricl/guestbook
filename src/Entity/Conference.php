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
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $city = null;
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $createdAt = null;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $international = null;
    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Comment",
     *     mappedBy="conference",
     *     orphanRemoval=true
     * )
     */
    private ?PersistentCollection $comments = null;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param ?DateTimeImmutable $createdAt
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return ?DateTimeImmutable
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
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

    public function addComment(Comment $comment) : self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setConference($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment) : self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            if ($comment->getConference() === $this) {
                $comment->setConference(null);
            }
        }

        return $this;
    }

    public function getComments(): ?PersistentCollection
    {
        return $this->comments;
    }
}
