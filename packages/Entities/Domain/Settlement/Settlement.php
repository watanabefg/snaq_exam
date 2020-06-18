<?php
namespace packages\Entities\Domain\Settlement;

use DateTime;

class Settlement
{
    private $id;
    private $settlement_date;

    /**
     * @param SettlementId $id
     * @param DateTime $settlement_date
     */
    public function __construct(SettlementId $id, DateTime $settlement_date)
    {
        $this->id = $id;
        $this->settlement_date = $settlement_date;
    }

    public function getId(): SettlementId
    {
        return $this->id;
    }

    public function getSettlementDate(): DateTime
    {
        return $this->settlement_date;
    }
}
