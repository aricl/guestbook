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
}
