<?php
namespace packages\Entities\Domain\Shipment;

class ShipmentId
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue(): Integer
    {
        return $this->value;
    }
}