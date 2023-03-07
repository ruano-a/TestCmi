<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait CreationDate
{
    #[ORM\Column(name: "creation_date", type: "datetime", nullable: false)]
    #[Groups(['read', 'read-recent-comments'])]
    protected \DateTime $creationDate;

    public function setCreationDate(\DateTime $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    public function initCreationDate(): self
    {
        $this->creationDate = new \Datetime();

        return $this;        
    }
}
