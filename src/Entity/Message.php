<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\StatusTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Message
{
    use StatusTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['threadMessages:read','message:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ["persist"], inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thread $thread = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[Groups(['threadMessages:read','message:read'])]
    private ?User $user_creator = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Groups(['threadMessages:read','message:read'])]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserCreator(): ?User
    {
        return $this->user_creator;
    }

    public function setUserCreator(?User $user_creator): static
    {
        $this->user_creator = $user_creator;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

}
