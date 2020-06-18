<?php
namespace packages\Entities\Domain\Settlement;

class SettlementId
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