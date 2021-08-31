<?php

namespace App\Entity;

use App\Entity\User\Tenant;
use App\Repository\CalendarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalendarRepository::class)
 */
class Calendar
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $repeat_end;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $frequency;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $color;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_repeated = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $repeat_id;

    /**
     * @ORM\ManyToOne(targetEntity=Tenant::class, inversedBy="calendars")
     */
    private $tenant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getRepeatEnd(): ?\DateTimeInterface
    {
        return $this->repeat_end;
    }

    public function setRepeatEnd(?\DateTimeInterface $repeat_end): self
    {
        $this->repeat_end = $repeat_end;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of frequency
     */ 
    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    /**
     * Set the value of frequency
     *
     * @return  self
     */ 
    public function setFrequency(?string $frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get the value of color
     */ 
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * Set the value of color
     *
     * @return  self
     */ 
    public function setColor(string $color)
    {
        $this->color = $color;

        return $this;
    }

    public function getIsRepeated(): ?bool
    {
        return $this->is_repeated;
    }

    public function setIsRepeated(bool $is_repeated): self
    {
        $this->is_repeated = $is_repeated;

        return $this;
    }

    public function getRepeatId(): ?int
    {
        return $this->repeat_id;
    }

    public function setRepeatId(?int $repeat_id): self
    {
        $this->repeat_id = $repeat_id;

        return $this;
    }

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function setTenant(?Tenant $tenant): self
    {
        $this->tenant = $tenant;

        return $this;
    }
}