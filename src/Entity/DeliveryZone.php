<?php

namespace App\Entity;

use App\Repository\DeliveryZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeliveryZoneRepository::class)
 */
class DeliveryZone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $priceDelivery;

    /**
     * @ORM\Column(type="float")
     */
    private $pricePickup;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="zone")
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPriceDelivery(): ?float
    {
        return $this->priceDelivery;
    }

    public function setPriceDelivery(float $priceDelivery): self
    {
        $this->priceDelivery = $priceDelivery;

        return $this;
    }

    public function getPricePickup(): ?float
    {
        return $this->pricePickup;
    }

    public function setPricePickup(float $pricePickup): self
    {
        $this->pricePickup = $pricePickup;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setZone($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getZone() === $this) {
                $order->setZone(null);
            }
        }

        return $this;
    }
}
