<?php
namespace packages\UseCases\Shipment\Create;

class ShipmentCreateRequest
{
    /**
     * @var string
     */
    private $settlement_date;

    /**
     * ShipmentCreateRequest constructor.
     * @param string $settlement_date
     */
    public function __construct(string $settlement_date)
    {
        $this->settlement_date = $settlement_date;
    }

    /**
     * @return string
     */
    public function getSettlementDate(): string
    {
        return $this->settlement_date;
    }
}
