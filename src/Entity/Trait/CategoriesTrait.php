<?php

namespace App\Entity\Trait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait CategoriesTrait
{
    #[ORM\Column(length: 255)]
    #[Groups(['tool:read', 'thread:read'])]
    private ?string $category = null;

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }
}