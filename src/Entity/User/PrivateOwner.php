<?php

namespace App\Entity\User;

use App\Entity\User\User;
use App\Repository\User\PrivateOwnerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrivateOwnerRepository::class)
 */
class PrivateOwner extends User
{
    /**
     * @ORM\Column(type="boolean")
     */
    private $public_phone = false;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $subscription_periodicity;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $subscription_start;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $subscription_end;

    public function getPublicPhone(): ?bool
    {
        return $this->public_phone;
    }

    public function setPublicPhone(bool $public_phone): self
    {
        $this->public_phone = $public_phone;

        return $this;
    }

    public function getSubscriptionPeriodicity(): ?string
    {
        return $this->subscription_periodicity;
    }

    public function setSubscriptionPeriodicity(?string $subscription_periodicity): self
    {
        $this->subscription_periodicity = $subscription_periodicity;

        return $this;
    }

    public function getSubscriptionStart(): ?\DateTimeImmutable
    {
        return $this->subscription_start;
    }

    public function setSubscriptionStart(?\DateTimeImmutable $subscription_start): self
    {
        $this->subscription_start = $subscription_start;

        return $this;
    }

    public function getSubscriptionEnd(): ?\DateTimeImmutable
    {
        return $this->subscription_end;
    }

    public function setSubscriptionEnd(?\DateTimeImmutable $subscription_end): self
    {
        $this->subscription_end = $subscription_end;

        return $this;
    }
}
