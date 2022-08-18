<?php

namespace App\Form\Data;

class OrderData
{
    public string $name;
    public string $email;
    public string $deliveryAddress;
    public string $deliveryCity;
    public string $deliveryPhone;
    public string $deliveryZone;
    public bool $pickupRefillments;
    public string $pickupAddress;
    public string $pickupCity;
    public string $pickupPhone;
    public string $pickupZone;
}