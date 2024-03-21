<?php

namespace App\Entity;

use App\Repository\UserThreadRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserThreadRepository::class)]
class UserThread
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userThreads')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['thread:read'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userThreads')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thread $thread = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    public function setThread(?Thread $thread): static
    {
        $this->thread = $thread;

        return $this;
    }
}
