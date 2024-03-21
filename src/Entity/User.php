<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\StatusTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use StatusTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tool:read', 'user:read', 'thread:read', 'message:read', 'threadMessages:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: "Cet email n'est pas valide.",
    )]
    #[Groups(['user:read', 'thread:read', 'message:read', 'threadMessages:read'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:read', 'thread:read', 'message:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Ignore]
    #[Assert\Regex(
        pattern : "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/",
        message : "Le mot de passe doit contenir au moins une minuscule, une majuscule et un chiffre, et doit avoir une longueur minimale de 8 caractères."
    )]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'thread:read', 'message:read', 'message:read', 'threadMessages:read'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'thread:read', 'message:read', 'message:read', 'threadMessages:read'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 14, nullable: true)]
    #[Assert\NotBlank]
    #[Groups(['user:read'])]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['user:read'])]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'offer:read'])]
    private ?string $domain = null;

    #[ORM\Column(length: 255, nullable: true)]
/*    #[Assert\Regex(
        pattern : "/^[+](\d{3})\)?(\d{3})(\d{5,6})$|^(\d{10,10})$/",
        message : "Veuillez entrer un numéro de téléphone valide."
    )]*/
    #[Assert\NotBlank]
    #[Groups(['user:read'])]
    private ?string $phone_number = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'offer:read'])]
    private ?string $company_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'thread:read', 'message:read'])]
    private ?string $avatar = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read'])]
    private ?string $cv = null;


    #[ORM\OneToMany(mappedBy: 'user_sender', targetEntity: Tool::class)]
    private Collection $tools;

    #[ORM\OneToMany(mappedBy: 'user_creator', targetEntity: Thread::class)]
    private Collection $threads;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserThread::class, orphanRemoval: true)]
    #[Groups(['user:read'])]
    private Collection $userThreads;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserTool::class, orphanRemoval: true)]
    #[Groups(['user:read'])]
    private Collection $userTools;

    #[ORM\OneToMany(mappedBy: 'user_creator', targetEntity: Offer::class, orphanRemoval: true)]
    #[Groups(['user:read'])]
    private Collection $offers;

    #[ORM\OneToMany(mappedBy: 'user_creator', targetEntity: Message::class)]
    #[Groups(['user:read'])]
    private Collection $messages;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'inscription')]
    #[Groups(['user:read'])]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'user_creator', targetEntity: Information::class)]
    #[Groups(['user:read'])]
    private Collection $information;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $resetToken;

    public function __construct()
    {
        $this->tools = new ArrayCollection();
        $this->threads = new ArrayCollection();
        $this->userThreads = new ArrayCollection();
        $this->userTools = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->information = new ArrayCollection();
    }

    public function __toString(): string
    {
        if ($this->company_name){
            return $this->company_name;
        }

        return $this->firstname . " " . $this->lastname;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): static
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(?string $company_name): static
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function getAvatarURL(): ?string
    {
        $avatar = $this->avatar;
        if ($avatar) {
            return "/uploads/avatar/" . $this->avatar;
        } else {
            return '/assets/images/placeholder_profil.png';
        }
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;

    }

    public function getCvURL(): ?string
    {
        $cv = $this->cv;
        if ($cv) {
            return "/uploads/cv/" . $this->cv;
        } else {
            return false;
        }
    }

    public function setCv(?string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }


    /**
     * @return Collection<int, Tool>
     */
    public function getTools(): Collection
    {
        return $this->tools;
    }

    public function addTool(Tool $tool): static
    {
        if (!$this->tools->contains($tool)) {
            $this->tools->add($tool);
            $tool->setUserSender($this);
        }

        return $this;
    }

    public function removeTool(Tool $tool): static
    {
        if ($this->tools->removeElement($tool)) {
            // set the owning side to null (unless already changed)
            if ($tool->getUserSender() === $this) {
                $tool->setUserSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Thread>
     */
    public function getThreads(): Collection
    {
        return $this->threads;
    }

    public function addThread(Thread $thread): static
    {
        if (!$this->threads->contains($thread)) {
            $this->threads->add($thread);
            $thread->setUserCreator($this);
        }

        return $this;
    }

    public function removeThread(Thread $thread): static
    {
        if ($this->threads->removeElement($thread)) {
            // set the owning side to null (unless already changed)
            if ($thread->getUserCreator() === $this) {
                $thread->setUserCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserThread>
     */
    public function getUserThreads(): Collection
    {
        return $this->userThreads;
    }

    public function addUserThread(UserThread $userThread): static
    {
        if (!$this->userThreads->contains($userThread)) {
            $this->userThreads->add($userThread);
            $userThread->setUser($this);
        }

        return $this;
    }

    public function removeUserThread(UserThread $userThread): static
    {
        if ($this->userThreads->removeElement($userThread)) {
            // set the owning side to null (unless already changed)
            if ($userThread->getUser() === $this) {
                $userThread->setUser(null);
            }
        }

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
            $userTool->setUser($this);
        }

        return $this;
    }

    public function removeUserTool(UserTool $userTool): static
    {

        if ($this->userTools->removeElement($userTool)) {
            // set the owning side to null (unless already changed)
            if ($userTool->getUser() === $this) {
                $userTool->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): static
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->setUserCreator($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getUserCreator() === $this) {
                $offer->setUserCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUserCreator($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUserCreator() === $this) {
                $message->setUserCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addInscription($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            $event->removeInscription($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Information>
     */
    public function getInformation(): Collection
    {
        return $this->information;
    }

    public function addInformation(Information $information): static
    {
        if (!$this->information->contains($information)) {
            $this->information->add($information);
            $information->setUserCreator($this);
        }

        return $this;
    }

    public function removeInformation(Information $information): static
    {
        if ($this->information->removeElement($information)) {
            // set the owning side to null (unless already changed)
            if ($information->getUserCreator() === $this) {
                $information->setUserCreator(null);
            }
        }

        return $this;
    }
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }



}
