<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use App\Entity\Traits\CreatedByUser;
use App\Entity\Traits\CreationDate;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    use CreatedByUser;
    use CreationDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    #[Groups(['read', 'read-recent-comments'])]
    #[Assert\Length(min: 5, minMessage: "username.length.min", max: 20, maxMessage: "username.length.max")]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private ?string $text = null;

    #[ORM\ManyToOne(targetEntity: Article::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'read-recent-comments'])]
    private ?Article $article = null;

    #[ORM\ManyToOne(targetEntity: Comment::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Comment $parentComment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getParentComment(): ?Comment
    {
        return $this->parentComment;
    }

    public function setParentComment(Comment $parentComment): self
    {
        $this->parentComment = $parentComment;

        return $this;
    }
}
