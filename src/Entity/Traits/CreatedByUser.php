<?php

namespace App\Entity\Traits;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait CreatedByUser
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'read-recent-comments'])]
    protected User $createdByUser;
    
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
