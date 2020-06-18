<?php
namespace packages\UseCases\Settlement\Create;

class SettlementCreateRequest
{
    /**
     * @var string
     */
    private $shipment_date;

    /**
     * SettlementCreateRequest constructor.
     * @param string $shipment_date
     */
    public function __construct(string $shipment_date)
    {
        $this->shipment_date = $shipment_date;
    }

    /**
     * @return string
     */
    public function getShipmentDate(): string
    {
        return $this->shipment_date;
    }
}