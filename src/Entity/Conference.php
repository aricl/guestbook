<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @ORM\Entity()
 * @UniqueEntity("slug")
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
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    private ?PersistentCollection $comments;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $slug = '';

    public function __construct(string $city, bool $international, int $year)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->city = $city;
        $this->createdAt = new DateTimeImmutable();
        $this->international = $international;
        $this->year = $year;
    }

    public function updateCity(string $city)
    {
        if ($city != $this->city) {
            $this->city = $city;
        }
    }

    public function updateYear(int $year)
    {
        if ($year != $this->year) {
            $this->year = $year;
        }
    }

    public function updateInternational(bool $international)
    {
        if ($international != $this->international) {
            $this->international = $international;
        }
    }

    public function computeSlug(SluggerInterface $slugger)
    {
        $this->slug = strval($slugger->slug(strval($this))->lower());
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function addComment($text, $author, $emailAddress, $photoFilename)
    {
        $comment = Comment::create($this, $author, $text, $emailAddress, $photoFilename);
        $this->comments->add($comment);
    }
}
