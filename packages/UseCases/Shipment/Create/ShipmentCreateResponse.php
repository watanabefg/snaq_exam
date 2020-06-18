<?php
namespace packages\UseCases\Shipment\Create;

class ShipmentCreateResponse
{
    /**
     * @var string[]
     */
    private $shipment_date;

    /**
     * @param string[] $shipment_date
     */
    public function __construct($shipment_date)
    {
        $this->shipment_date = $shipment_date;
    }

    /**
     * @return string[]
     */
    public function getCreatedShipmentDate()
    {
        return $this->shipment_date;
    }
}                       