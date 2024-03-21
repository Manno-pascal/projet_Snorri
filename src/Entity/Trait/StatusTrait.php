<?php

namespace App\Entity\Trait;

use App\Enum\StatusEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait StatusTrait
{
    #[Groups(['tool:read', 'thread:read'])]
    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;

    }
}