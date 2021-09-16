<?php

namespace App\Entity\User;

use App\Entity\Document;
use App\Entity\Document\DocHasSeen;
use App\Entity\Message\Message;
use App\Entity\Message\UserHasMessageRead;
use App\Entity\Property\Property;
use App\Repository\User\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "private_owner" = "PrivateOwner", "owner" = "Owner", "agent" = "Agent", "tenant" = "Tenant"})
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $phone_number;

    /**
     * @var \DateTimeImmutable $created_at
     * 
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $address;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_active = true;

    /**
     * @ORM\OneToMany(targetEntity=DocHasSeen::class, mappedBy="user", orphanRemoval=true)
     */
    private $documents;

    /**
     * @ORM\OneToMany(targetEntity=Property::class, mappedBy="manager_property")
     */
    protected $manager_properties;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender")
     */
    protected $sender_messages;

    /**
     * @ORM\OneToMany(targetEntity=UserHasMessageRead::class, mappedBy="recipient")
     */
    protected $user_recipient_messages;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->manager_properties = new ArrayCollection();
        $this->sender_messages = new ArrayCollection();
        $this->user_recipient_messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return Collection|DocHasSeen[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(DocHasSeen $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setUser($this);
        }

        return $this;
    }

    public function removeDocument(DocHasSeen $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getUser() === $this) {
                $document->setUser(null);
            }
        }

        return $this;
    }
    
    public function __toString()
    {
        return $this->lastname . ' ' . $this->firstname . ' ' . $this->id;
    }

    /**
     * @return Collection|Property[]
     */
    public function getManagerProperties(): Collection
    {
        return $this->manager_properties;
    }

    public function addManagerProperty(Property $managerProperty): self
    {
        if (!$this->manager_properties->contains($managerProperty)) {
            $this->manager_properties[] = $managerProperty;
            $managerProperty->setManagerProperty($this);
        }

        return $this;
    }

    public function removeManagerProperty(Property $managerProperty): self
    {
        if ($this->manager_properties->removeElement($managerProperty)) {
            // set the owning side to null (unless already changed)
            if ($managerProperty->getManagerProperty() === $this) {
                $managerProperty->setManagerProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSenderMessages(): Collection
    {
        return $this->sender_messages;
    }

    public function addSenderMessage(Message $senderMessage): self
    {
        if (!$this->sender_messages->contains($senderMessage)) {
            $this->sender_messages[] = $senderMessage;
            $senderMessage->setSender($this);
        }

        return $this;
    }

    public function removeSenderMessage(Message $senderMessage): self
    {
        if ($this->sender_messages->removeElement($senderMessage)) {
            // set the owning side to null (unless already changed)
            if ($senderMessage->getSender() === $this) {
                $senderMessage->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserHasMessageRead[]
     */
    public function getUserRecipientMessages(): Collection
    {
        return $this->user_recipient_messages;
    }

    public function addUserRecipientMessage(UserHasMessageRead $userRecipientMessage): self
    {
        if (!$this->user_recipient_messages->contains($userRecipientMessage)) {
            $this->user_recipient_messages[] = $userRecipientMessage;
            $userRecipientMessage->setRecipient($this);
        }

        return $this;
    }

    public function removeUserRecipientMessage(UserHasMessageRead $userRecipientMessage): self
    {
        if ($this->user_recipient_messages->removeElement($userRecipientMessage)) {
            // set the owning side to null (unless already changed)
            if ($userRecipientMessage->getRecipient() === $this) {
                $userRecipientMessage->setRecipient(null);
            }
        }

        return $this;
    }

}
