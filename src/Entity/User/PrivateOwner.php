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
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
