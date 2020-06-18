<?php
namespace packages\UseCases\Settlement\Create;

class SettlementCreateResponse
{
    /**
     * @var String
     */
    private $settlement_date;

    /**
     * @param String $settlement_date
     */
    public function __construct($settlement_date)
    {
        $this->settlement_date = $settlement_date;
    }

    /**
     * @return String
     */
    public function getCreatedSettlementDate()
    {
        return $this->settlement_date;
    }
}                       