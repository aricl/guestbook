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
     * @ORM\Column(type="string")
     */
    private string $author;
    /**
     * @ORM\Column(type="text")
     */
    private string $text;
    /**
     * @ORM\Column(type="string")
     */
    private string $email;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conference", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private Conference $conference;
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $photoFilename;

    private function __construct(
        Conference $conference,
        string $author,
        string $text,
        string $emailAddress,
        ?string $photoFilename = null
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->author = $author;
        $this->text = $text;
        $this->email = $emailAddress;
        $this->createdAt = new DateTimeImmutable();
        $this->conference = $conference;
        $this->photoFilename = $photoFilename;
    }

    public static function createWithPhoto(
        Conference $conference,
        string $author,
        string $text,
        string $emailAddress,
        string $photoFilename
    ): Comment
    {
        return new self(
            $conference,
            $author,
            $text,
            $emailAddress,
            $photoFilename
        );
    }

    public static function createWithoutPhoto(
        Conference $conference,
        string $author,
        string $text,
        string $emailAddress
    ): Comment
    {
        return new self(
            $conference,
            $author,
            $text,
            $emailAddress
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getConference(): Conference
    {
        return $this->conference;
    }

    public function getPhotoFilename(): ?string
    {
        return $this->photoFilename;
    }
}
