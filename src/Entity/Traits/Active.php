<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Active
{
    #[ORM\Column(name: "active", type: "boolean", nullable: false)]
    protected bool $active = true;

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }
}
