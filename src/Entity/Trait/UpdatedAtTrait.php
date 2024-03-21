<?php


namespace App\Entity\Trait;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


trait UpdatedAtTrait
{


    #[ORM\Column(nullable: true)]
    #[Groups(['thread:read','threadMessages:read','message:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->updatedAt = $createdAt;

        return $this;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): static
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }


}