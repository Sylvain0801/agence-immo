<?php

namespace App\Entity\User;

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

    public function __construct()
    {
        parent::__construct();
        $this->tenant_properties = new ArrayCollection();
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
}
