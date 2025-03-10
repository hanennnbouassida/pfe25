<?php

namespace App\Entity;

use App\Repository\BusinessRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BusinessRepository::class)]
class Business extends User
{
   
    #[ORM\Column(length: 255)]
    private ?string $businessName = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    /**
     * @var string|null
     */
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ig_TT = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fb_link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tt_link = null;

    public function getBusinessName(): ?string
    {
        return $this->businessName;
    }

    public function setBusinessName(string $businessName): static
    {
        $this->businessName = $businessName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getIgTT(): ?string
    {
        return $this->ig_TT;
    }

    public function setIgTT(?string $ig_TT): static
    {
        $this->ig_TT = $ig_TT;

        return $this;
    }

    public function getFbLink(): ?string
    {
        return $this->fb_link;
    }

    public function setFbLink(?string $fb_link): static
    {
        $this->fb_link = $fb_link;

        return $this;
    }

    public function getTtLink(): ?string
    {
        return $this->tt_link;
    }

    public function setTtLink(?string $tt_link): static
    {
        $this->tt_link = $tt_link;

        return $this;
    }
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
