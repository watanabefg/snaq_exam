<?php
namespace packages\UseCases\Settlement\Create;

use DateTime;

class SettlementCreateInteractor implements SettlementCreateUseCaseInterface
{
    /**
     * @param SettlementCreateRequest
     * @return SettlementCreateResponse
     */
    public function handle(SettlementCreateRequest $request)
    {
        if (!$this->validateDate($request->getShipmentDate(), 'Y/n/j')) {
            return new SettlementCreateResponse('発送日をY/n/j形式で入力してください');
        }
        $shipment_date = new DateTime($request->getShipmentDate());

        $settlement_date = $this->deriveSettlementDate($shipment_date);
        return new SettlementCreateResponse($settlement_date->format('Y-m-d'));
    }

    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * 発送日の前週月曜日が決済日 (ex. 5/11~5/15発送は5/4決済)
     * @param DateTime $shipment_date
     */
    public function deriveSettlementDate($shipment_date) {
        return $shipment_date->modify('-1 week')->modify('monday this week');
    }
}