<?php

namespace App\Entity\User;

use App\Entity\Property\Property;
use App\Repository\User\OwnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OwnerRepository::class)
 */
class Owner extends User
{
    /**
     * @ORM\OneToMany(targetEntity=Property::class, mappedBy="owner_property")
     */
    private $owner_properties;

    /**
     * @ORM\ManyToOne(targetEntity=Agent::class, inversedBy="owners")
     */
    private $agent;

    public function __construct()
    {
        parent::__construct();
        $this->owner_properties = new ArrayCollection();
    }

    /**
     * @return Collection|Property[]
     */
    public function getOwnerProperties(): Collection
    {
        return $this->owner_properties;
    }

    public function addOwnerProperty(Property $ownerProperty): self
    {
        if (!$this->owner_properties->contains($ownerProperty)) {
            $this->owner_properties[] = $ownerProperty;
            $ownerProperty->setOwnerProperty($this);
        }

        return $this;
    }

    public function removeOwnerProperty(Property $ownerProperty): self
    {
        if ($this->owner_properties->removeElement($ownerProperty)) {
            // set the owning side to null (unless already changed)
            if ($ownerProperty->getOwnerProperty() === $this) {
                $ownerProperty->setOwnerProperty(null);
            }
        }

        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }
}
