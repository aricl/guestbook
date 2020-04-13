<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private string $id;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $author;
    /**
     * @ORM\Column(type="text")
     */
    private ?string $text;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $email;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conference", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Conference $conference;
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $photoFilename;

    private function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return Conference
     */
    public function getConference(): Conference
    {
        return $this->conference;
    }

    /**
     * @param Conference $conference
     */
    public function setConference(Conference $conference): void
    {
        $this->conference = $conference;
    }

    /**
     * @return string|null
     */
    public function getPhotoFilename(): ?string
    {
        return $this->photoFilename;
    }

    /**
     * @param string|null $photoFilename
     */
    public function setPhotoFilename(?string $photoFilename): void
    {
        $this->photoFilename = $photoFilename;
    }
}
