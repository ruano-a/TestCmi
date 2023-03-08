<?php

namespace App\Entity;

use App\Entity\Comment;
use App\Entity\Traits\Active;
use App\Entity\Traits\CreatedByUser;
use App\Entity\Traits\CreationDate;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    use Active;
    use CreatedByUser;
    use CreationDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read', 'read-recent-comments'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'read-recent-comments'])]
    #[Assert\Length(min: 5, minMessage: "title.length.min", max: 255, maxMessage: "title.length.max")]
    private ?string $title = null;

    #[ORM\Column(length: 4095)]
    #[Groups(['read'])]
    #[Assert\Length(min: 5, minMessage: "content.length.min", max: 4095, maxMessage: "content.length.max")]
    private ?string $content = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'article')]
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }
}
