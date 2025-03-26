<?php

namespace App\Entity;

use App\Repository\BusinessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(length: 255)]
    private ?string $ownerName = null;

    #[ORM\Column(length: 255)]
    private ?string $ownerLastName = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(type: 'string', length: 255, options: ['default' => 'pending'])]
    private ?string $status = 'pending';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $logoBase64 = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'business')]
    private Collection $products;

  

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    
    public function __construct()
    {
        $this->products = new ArrayCollection();

    } // Values: pending, approved, rejected

   
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

    public function getOwnerName(): ?string
    {
        return $this->ownerName;
    }

    public function setOwnerName(string $ownerName): static
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    public function getOwnerLastName(): ?string
    {
        return $this->ownerLastName;
    }

    public function setOwnerLastName(string $ownerLastName): static
    {
        $this->ownerLastName = $ownerLastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
        
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setBusiness($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getBusiness() === $this) {
                $product->setBusiness(null);
            }
        }

        return $this;
    }
    public function getLogoBase64(): ?string
    {
        return $this->logoBase64;
    }

    public function setLogoBase64(?string $logoBase64): self
    {
        $this->logoBase64 = $logoBase64;
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }
   
}
