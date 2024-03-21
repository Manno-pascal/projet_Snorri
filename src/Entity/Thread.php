<?php

namespace App\Entity;

use App\Entity\Trait\CategoriesTrait;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\StatusTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\ThreadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Thread
{
    use CategoriesTrait;
    use StatusTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['thread:read'])]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Groups(['thread:read'])]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'threads')]
    #[Groups(['thread:read'])]
    private ?User $user_creator = null;

    #[ORM\OneToMany(mappedBy: 'thread', targetEntity: UserThread::class, orphanRemoval: true)]
    #[Groups(['thread:read'])]
    private Collection $userThreads;

    #[ORM\OneToMany(mappedBy: 'thread', targetEntity: Message::class, orphanRemoval: true)]
    #[Groups(['thread:read','threadMessages:read'])]
    private Collection $messages;




    public function __construct()
    {
        $this->userThreads = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
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

    public function getUserCreator(): ?User
    {
        return $this->user_creator;
    }

    public function setUserCreator(?User $user_creator): static
    {
        $this->user_creator = $user_creator;

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
            $userThread->setThread($this);
        }

        return $this;
    }

    public function removeUserThread(UserThread $userThread): static
    {
        if ($this->userThreads->removeElement($userThread)) {
            // set the owning side to null (unless already changed)
            if ($userThread->getThread() === $this) {
                $userThread->setThread(null);
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
            $message->setThread($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getThread() === $this) {
                $message->setThread(null);
            }
        }

        return $this;
    }


}
