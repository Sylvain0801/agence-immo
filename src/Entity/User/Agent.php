<?php

namespace App\Entity\User;

use App\Repository\User\AgentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgentRepository::class)
 */
class Agent extends User
{
    /**
     * @ORM\Column(type="boolean")
     */
    private $phone_public = true;

    public function getPhonePublic(): ?bool
    {
        return $this->phone_public;
    }

    public function setPhonePublic(bool $phone_public): self
    {
        $this->phone_public = $phone_public;

        return $this;
    }
}
