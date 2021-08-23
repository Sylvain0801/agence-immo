<?php

namespace App\Entity\User;

use App\Entity\Property\Property;
use App\Repository\User\TenantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TenantRepository::class)
 */
class Tenant extends User
{
    /**
     * @ORM\ManyToOne(targetEntity=Property::class, inversedBy="property_tenants")
     */
    private $tenant_property;

    public function getTenantProperty(): ?Property
    {
        return $this->tenant_property;
    }

    public function setTenantProperty(?Property $tenant_property): self
    {
        $this->tenant_property = $tenant_property;

        return $this;
    }
}
