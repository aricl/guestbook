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
        string $email,
        ?string $photoFilename = null
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->author = $author;
        $this->text = $text;
        $this->email = $email;
        $this->createdAt = new DateTimeImmutable();
        $this->conference = $conference;
        $this->photoFilename = $photoFilename;
    }

    public static function create(Conference $conference, string $author, string $text, string $email): Comment
    {
        return new self($conference, $author, $text, $email);
    }

    public function createWithPhoto(
        Conference $conference,
        string $author,
        string $text,
        string $email,
        string $photoFilename
    ): Comment
    {
        return new self(
            $conference,
            $author,
            $text,
            $email,
            $photoFilename
        );
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Conference
     */
    public function getConference(): Conference
    {
        return $this->conference;
    }

    /**
     * @return string|null
     */
    public function getPhotoFilename(): ?string
    {
        return $this->photoFilename;
    }
}
