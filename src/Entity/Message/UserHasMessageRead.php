<?php

namespace App\Entity\Message;

use App\Entity\User\User;
use App\Repository\Message\UserHasMessageReadRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserHasMessageReadRepository::class)
 */
class UserHasMessageRead
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Message::class, inversedBy="recipients")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="user_recipient_messages")
     */
    private $recipient;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_read = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }
}
