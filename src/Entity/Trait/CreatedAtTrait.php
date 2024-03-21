<?php


namespace App\Entity\Trait;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


trait CreatedAtTrait
{


    #[ORM\Column]
    #[Groups(['thread:read','threadMessages:read','message:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): static
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }


}