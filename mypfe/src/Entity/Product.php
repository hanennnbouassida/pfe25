<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageproduct = null;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imageproduct')]
    #[Assert\Image(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Veuillez télécharger une image valide (JPEG ou PNG)'
    )]
    private ?File $photoFile = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updatedAt = null;

    //--------------------------------------------------------------------------------
    #[ORM\Column(length: 255)]
    private ?string $product_name = null;

    #[ORM\Column(length: 255)]
    private ?string $product_description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
     #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Business $business = null;

    #[ORM\Column(length: 255, options: ["default" => "pending"])] //values : pending, posted, refused
    private ?string $status = "pending";

    #[ORM\Column(length: 255)]
    private ?string $qte = null;

    #[ORM\Column(length: 255, options: ['default' => 'available'])] //values : out of stock, available
    private ?string $StockStats = 'available';
    
    //promotion -----------------------------------------------------------------
    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    private ?float $discountPercentage = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $promotionStartDate = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $promotionEndDate = null;
//--------------------------------------------------------------------------------
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): static
    {
        $this->product_name = $product_name;

        return $this;
    }

    public function getProductDescription(): ?string
    {
        return $this->product_description;
    }

    public function setProductDescription(string $product_description): static
    {
        $this->product_description = $product_description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getBusiness(): ?Business
    {
        return $this->business;
    }

    public function setBusiness(?Business $business): static
    {
        $this->business = $business;

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

    public function getimageproduct(): ?string
    {
        return $this->imageproduct;
    }

    public function setimageproduct(?string $imageproduct): self
    {
        $this->imageproduct = $imageproduct;
        return $this;
    }

    public function setPhotoFile(?File $photoFile = null): void
    {
        $this->photoFile = $photoFile;

        if ($photoFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getQte(): ?string
    {
        return $this->qte;
    }

    public function setQte(string $qte): static
    {
        $this->qte = $qte;

        return $this;
    }

    public function getStockStats(): ?string
    {
        return $this->StockStats;
    }

    public function setStockStats(string $StockStats): static
    {
        $this->StockStats = $StockStats;

        return $this;
    }


    //promotion 
    public function getDiscountPercentage(): ?float
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(?float $discountPercentage): self
    {
        $this->discountPercentage = $discountPercentage;

        return $this;
    }

    public function getPromotionStartDate(): ?\DateTimeInterface
    {
        return $this->promotionStartDate;
    }

    public function setPromotionStartDate(?\DateTimeInterface $promotionStartDate): self
    {
        $this->promotionStartDate = $promotionStartDate;

        return $this;
    }

    public function getPromotionEndDate(): ?\DateTimeInterface
    {
        return $this->promotionEndDate;
    }

    public function setPromotionEndDate(?\DateTimeInterface $promotionEndDate): self
    {
        $this->promotionEndDate = $promotionEndDate;

        return $this;
    }
}
