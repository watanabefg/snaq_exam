<?php
namespace packages\Entities\Domain\Shipment;

use DateTime;

class Shipment
{
    private $id;
    private $shipment_date;

   /**
     * @param ShipmentId $id
     * @param DateTime $shipment_date
     */
    public function __construct(ShipmentId $id, DateTime $shipment_date)
    {
        $this->id = $id;
        $this->shipment_date = $shipment_date;
    }

    public function getId(): ShipmentId
    {
        return $this->id;
    }

    public function getShipmentDate(): DateTime
    {
        return $this->shipment_date;
    }
}
