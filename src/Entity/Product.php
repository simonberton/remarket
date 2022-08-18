<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    CONST TYPE_UNITARY = 'Unitario';
    CONST TYPE_SOLID = 'Solido';
    CONST TYPE_LIQUID = 'Liquido';
    CONST TYPES = [
        self::TYPE_UNITARY => self::TYPE_UNITARY,
        self::TYPE_SOLID => self::TYPE_SOLID,
        self::TYPE_LIQUID => self::TYPE_LIQUID
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=6)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     */
    private $priceDivider;

     /**
      * @ORM\Column(type="text")
      */
     private $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
     private $mainImageFilename;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $secondaryImageFilename;

    /**
     * @ORM\Column(type="string", length=12)
     * Unitaroio, Solido, Liquido
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $divisible;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $divisibleBy;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=Envase::class, inversedBy="products")
     */
    private $envases;

    public function __construct()
    {
        $this->envases = new ArrayCollection();
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @param mixed $divisible
     */
    public function setDivisible($divisible): void
    {
        $this->divisible = $divisible;
    }

    /**
     * @param mixed $divisibleBy
     */
    public function setDivisibleBy($divisibleBy): void
    {
        $this->divisibleBy = $divisibleBy;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getMainImageFilename()
    {
        return $this->mainImageFilename;
    }

    /**
     * @param mixed $mainImageFilename
     */
    public function setMainImageFilename($mainImageFilename): void
    {
        $this->mainImageFilename = $mainImageFilename;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function isDivisible()
    {
        return $this->divisible;
    }

    /**
     * @return mixed
     */
    public function getDivisible()
    {
        return $this->divisible;
    }

    /**
     * @return mixed
     */
    public function getDivisibleBy()
    {
        return $this->divisibleBy;
    }

    /**
     * @return mixed
     */
    public function getSecondaryImageFilename()
    {
        return $this->secondaryImageFilename;
    }

    /**
     * @param mixed $secondaryImageFilename
     */
    public function setSecondaryImageFilename($secondaryImageFilename): void
    {
        $this->secondaryImageFilename = $secondaryImageFilename;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getPriceDivider()
    {
        return $this->priceDivider;
    }

    /**
     * @param mixed $priceDivider
     */
    public function setPriceDivider($priceDivider): void
    {
        $this->priceDivider = $priceDivider;
    }


    public function getTypeForFront(): string
    {
        switch ($this->type) {
            case 'Solido':
                return $this->priceDivider > 999 ? 'Kilo' : $this->priceDivider . ' Gramos';
            break;
            case 'Liquido':
                return $this->priceDivider > 999 ? 'Litro' : $this->priceDivider . ' Mililitros';
            break;
            default:
                return 'Unidad';
            break;
        }
    }

    /**
     * @return Collection<int, Envase>
     */
    public function getEnvases(): Collection
    {
        return $this->envases;
    }

    public function addEnvase(Envase $envase): self
    {
        if (!$this->envases->contains($envase)) {
            $this->envases[] = $envase;
            $envase->addProduct($this);
        }

        return $this;
    }

    public function removeEnvase(Envase $envase): self
    {
        if ($this->envases->removeElement($envase)) {
            $envase->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku): void
    {
        $this->sku = $sku;
    }
}