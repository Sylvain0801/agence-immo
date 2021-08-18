<?php

namespace App\Entity\Property;

use App\Entity\User\Owner;
use App\Entity\User\User;
use App\Repository\Property\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropertyRepository::class)
 */
class Property
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $transaction_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $area;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $energy;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $ges;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=PropertyType::class, inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $property_type;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="property")
     */
    private $offers;

    /**
     * @ORM\ManyToMany(targetEntity=Option::class, mappedBy="property")
     */
    private $options;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="properties")
     */
    private $manager;

    /**
     * @ORM\ManyToOne(targetEntity=Owner::class, inversedBy="properties")
     */
    private $owner;

    /**
     * @ORM\Column(type="integer")
     */
    private int $property_ad_count = 0;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionType(): ?string
    {
        return $this->transaction_type;
    }

    public function setTransactionType(string $transaction_type): self
    {
        $this->transaction_type = $transaction_type;

        return $this;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(int $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getEnergy(): ?string
    {
        return $this->energy;
    }

    public function setEnergy(string $energy): self
    {
        $this->energy = $energy;

        return $this;
    }

    public function getGes(): ?string
    {
        return $this->ges;
    }

    public function setGes(string $ges): self
    {
        $this->ges = $ges;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPropertyType(): ?PropertyType
    {
        return $this->property_type;
    }

    public function setPropertyType(?PropertyType $property_type): self
    {
        $this->property_type = $property_type;

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setProperty($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getProperty() === $this) {
                $offer->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addProperty($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            $option->removeProperty($this);
        }

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(?Owner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getPropertyAdCount(): ?int
    {
        return $this->property_ad_count;
    }

    public function setPropertyAdCount(int $property_ad_count) :self
    {
        $this->property_ad_count = $property_ad_count;

        return $this;
    }
}
