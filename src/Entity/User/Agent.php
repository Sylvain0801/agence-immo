<?php

namespace App\Entity\User;

use App\Repository\User\AgentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=Owner::class, mappedBy="agent")
     */
    private $owners;

    public function __construct()
    {
        parent::__construct();
        $this->owners = new ArrayCollection();
    }

    public function getPhonePublic(): ?bool
    {
        return $this->phone_public;
    }

    public function setPhonePublic(bool $phone_public): self
    {
        $this->phone_public = $phone_public;

        return $this;
    }

    /**
     * @return Collection|Owner[]
     */
    public function getOwners(): Collection
    {
        return $this->owners;
    }

    public function addOwner(Owner $owner): self
    {
        if (!$this->owners->contains($owner)) {
            $this->owners[] = $owner;
            $owner->setAgent($this);
        }

        return $this;
    }

    public function removeOwner(Owner $owner): self
    {
        if ($this->owners->removeElement($owner)) {
            // set the owning side to null (unless already changed)
            if ($owner->getAgent() === $this) {
                $owner->setAgent(null);
            }
        }

        return $this;
    }
}
