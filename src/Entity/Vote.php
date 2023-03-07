<?php

namespace App\Entity;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Traits\CreatedByUser;
use App\Entity\Traits\CreationDate;
use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    use CreatedByUser;
    use CreationDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Choice([-1, 1])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private ?int $value = null;

    #[ORM\ManyToOne(targetEntity: Comment::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Comment $comment = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    protected User $createdByUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
    
    public function getCreatedByUser(): ?User
    {
        return $this->createdByUser;
    }

    public function setCreatedByUser(User $createdByUser): self
    {
        $this->createdByUser = $createdByUser;

        return $this;
    }
}
