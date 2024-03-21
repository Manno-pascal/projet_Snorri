<?php

namespace App\Entity;

use App\Entity\Trait\CategoriesTrait;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\StatusTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\ToolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ToolRepository::class)]
#[ORM\HasLifecycleCallbacks]
class
Tool
{
    use CategoriesTrait;
    use StatusTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tool:read'])]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Groups(['tool:read'])]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['tool:read'])]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tool:read'])]
    #[Assert\Url]
    #[Assert\NoSuspiciousCharacters]
    #[Assert\NotBlank]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['tool:read'])]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'tools')]
    private ?User $user_sender = null;

    #[ORM\OneToMany(mappedBy: 'tool', targetEntity: UserTool::class, orphanRemoval: true)]
    #[Groups(['tool:read'])]
    private Collection $userTools;

    public function __construct()
    {
        $this->userTools = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getImage(): ?string
    {
        $image = $this->image;
        if ($image){
            return "/uploads/image/".$this->image;
        }
        return '/assets/images/placeholder_picture.jpg';
    }

    public function getImageUrl(): ?string
    {
        $image = $this->image;
        if ($image){
            return "/uploads/tool/".$this->image;
        }
            return '/assets/images/placeholder_picture.jpg';

    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getUserSender(): ?User
    {
        return $this->user_sender;
    }

    public function setUserSender(?User $user_sender): static
    {
        $this->user_sender = $user_sender;

        return $this;
    }

    /**
     * @return Collection<int, UserTool>
     */
    public function getUserTools(): Collection
    {
        return $this->userTools;
    }

    public function addUserTool(UserTool $userTool): static
    {
        if (!$this->userTools->contains($userTool)) {
            $this->userTools->add($userTool);
            $userTool->setTool($this);
        }

        return $this;
    }

    public function removeUserTool(UserTool $userTool): static
    {
        if ($this->userTools->removeElement($userTool)) {
            // set the owning side to null (unless already changed)
            if ($userTool->getTool() === $this) {
                $userTool->setTool(null);
            }
        }

        return $this;
    }

}
