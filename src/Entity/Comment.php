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

    public function __construct(Conference $conference, string $author, string $text, string $email)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->author = $author;
        $this->text = $text;
        $this->email = $email;
        $this->createdAt = new DateTimeImmutable();
        $this->conference = $conference;
    }
}
