<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="date")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deliveryAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deliveryCity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deliveryPhone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $invoiceEmail;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pickupRefillments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pickupAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pickupCity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pickupPhone;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pickupPrice;

    /**
     * @ORM\Column(type="json", nullable=false)
     */
    private $cart;

    /**
     * @ORM\ManyToOne(targetEntity=Coupon::class, inversedBy="orders")
     */
    private $coupon;

    /**
     * @ORM\ManyToOne(targetEntity=DeliveryZone::class, inversedBy="orders")
     */
    private $zone;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(string $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getDeliveryCity(): ?string
    {
        return $this->deliveryCity;
    }

    public function setDeliveryCity(string $deliveryCity): self
    {
        $this->deliveryCity = $deliveryCity;

        return $this;
    }

    public function getDeliveryPhone(): ?string
    {
        return $this->deliveryPhone;
    }

    public function setDeliveryPhone(string $deliveryPhone): self
    {
        $this->deliveryPhone = $deliveryPhone;

        return $this;
    }

    public function getInvoiceEmail(): ?string
    {
        return $this->invoiceEmail;
    }

    public function setInvoiceEmail(string $invoiceEmail): self
    {
        $this->invoiceEmail = $invoiceEmail;

        return $this;
    }

    public function isPickupRefillments(): ?bool
    {
        return $this->pickupRefillments;
    }

    public function setPickupRefillments(bool $pickupRefillments): self
    {
        $this->pickupRefillments = $pickupRefillments;

        return $this;
    }

    public function getPickupAddress(): ?string
    {
        return $this->pickupAddress;
    }

    public function setPickupAddress(string $pickupAddress): self
    {
        $this->pickupAddress = $pickupAddress;

        return $this;
    }

    public function getPickupCity(): ?string
    {
        return $this->pickupCity;
    }

    public function setPickupCity(string $pickupCity): self
    {
        $this->pickupCity = $pickupCity;

        return $this;
    }

    public function getPickupPhone(): ?string
    {
        return $this->pickupPhone;
    }

    public function setPickupPhone(string $pickupPhone): self
    {
        $this->pickupPhone = $pickupPhone;

        return $this;
    }

    public function getPickupPrice(): ?float
    {
        return $this->pickupPrice;
    }

    public function setPickupPrice(?float $pickupPrice): self
    {
        $this->pickupPrice = $pickupPrice;

        return $this;
    }

    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    public function setCoupon(?Coupon $coupon): self
    {
        $this->coupon = $coupon;

        return $this;
    }

    public function getZone(): ?DeliveryZone
    {
        return $this->zone;
    }

    public function setZone(?DeliveryZone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param mixed $cart
     */
    public function setCart($cart): void
    {
        $this->cart = $cart;
    }
}
