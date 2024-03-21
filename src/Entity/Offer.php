<?php

namespace App\Entity;

use App\Entity\Trait\ContractTypesTrait;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\StatusTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\OfferRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
//Précise que cette entité peut avoir des evenements. à l'occurence lors de la creation d'une instance (d'une nouvelle offre)
// on ajoute automatiquement avant l'enregistrement en bdd une date de création dans le trait CreatedAT ainsi qu'une date
// de modification dans le trait updatedAt
#[ORM\HasLifecycleCallbacks]
class Offer
{
    //Ici on importe les traits. Les traits sont des morceaux de codes en php qu'on peut importer dans des class qui n'ont
    // rien à voir entre elles. Ici on importes des colonnes en communs dans plusieurs entitées de l'application
    // voir les fichiers contenus dans *src/Entity/Trait/*
    use StatusTrait;
    use ContractTypesTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    //Déclaration d'une colonne de l'entité à l'occurence la colonne id
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['offer:read'])]
    private ?int $id = null;

    // Déclaration d'une relation MtO avec user creator. Many coté offer et one coté user car un utilisateur peut avoir
    // plusieurs offres mais une offre a uniquement un createur
    // la colonne ne peut pas etre nulle et elle fait partie du groupe offer:read, lors de la normalisation elle
    // sera donc renvoyée en front
    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['offer:read'])]
    private ?User $user_creator = null;

    #[ORM\Column(length: 255)]
    //Ici il s'agit d'une validation, un assert permet de verifier si la valeur de cette colonne dans une instance est
    // est valide, si elle ne l'ai pas l'enregistrement en bdd ne se fera pas. C'est le cas pour l'ensemble des assert.
        // Ici il s'agit d'un notBlank donc la valeur doit etre définie dans le formulaire
    #[Assert\NotBlank (message: 'Ce champ est requis')]
    #[Groups(['offer:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['offer:read'])]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['offer:read'])]
    private ?string $salary = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Groups(['offer:read'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\NoSuspiciousCharacters]
    #[Groups(['offer:read'])]
    private ?string $description = null;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(?string $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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

}
