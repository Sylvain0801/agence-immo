<?php

namespace App\Entity\User;

use App\Entity\Calendar;
use App\Entity\Property\Property;
use App\Repository\User\TenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TenantRepository::class)
 */
class Tenant extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Property::class, mappedBy="property_tenants")
     */
    private $tenant_properties;

    /**
     * @ORM\OneToMany(targetEntity=Calendar::class, mappedBy="tenant")
     */
    private $calendars;

    public function __construct()
    {
        parent::__construct();
        $this->tenant_properties = new ArrayCollection();
        $this->calendars = new ArrayCollection();
    }

    /**
     * @return Collection|Property[]
     */
    public function getTenantProperties(): Collection
    {
        return $this->tenant_properties;
    }

    public function addTenantProperty(Property $tenantProperty): self
    {
        if (!$this->tenant_properties->contains($tenantProperty)) {
            $this->tenant_properties[] = $tenantProperty;
            $tenantProperty->addPropertyTenant($this);
        }

        return $this;
    }

    public function removeTenantProperty(Property $tenantProperty): self
    {
        if ($this->tenant_properties->removeElement($tenantProperty)) {
            $tenantProperty->removePropertyTenant($this);
        }

        return $this;
    }

    /**
     * @return Collection|Calendar[]
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    public function addCalendar(Calendar $calendar): self
    {
        if (!$this->calendars->contains($calendar)) {
            $this->calendars[] = $calendar;
            $calendar->setTenant($this);
        }

        return $this;
    }

    public function removeCalendar(Calendar $calendar): self
    {
        if ($this->calendars->removeElement($calendar)) {
            // set the owning side to null (unless already changed)
            if ($calendar->getTenant() === $this) {
                $calendar->setTenant(null);
            }
        }

        return $this;
    }
}
