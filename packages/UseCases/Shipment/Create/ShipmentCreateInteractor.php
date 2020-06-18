<?php
namespace packages\UseCases\Shipment\Create;

use DateTime;
use packages\Entities\Domain\Shipment\Shipment;
use packages\Entities\Domain\Shipment\ShipmentId;

class ShipmentCreateInteractor implements ShipmentCreateUseCaseInterface
{
    public function handle(ShipmentCreateRequest $request)
    {
        if (!$this->validateDate($request->getSettlementDate(), 'Y/n/j')) {
            return new ShipmentCreateResponse(['決済日をY/n/j形式で入力してください']);
        }
        $settlement_date = new DateTime($request->getSettlementDate());

        $shipmentId = new ShipmentId(1);
        $createdShipment[] = new Shipment($shipmentId, $this->firstTimeProcessing($settlement_date));
        // NOTE: DB保存は不要という仕様だったため
        $createdShipmentDate[] = $createdShipment[0]->getShipmentDate()->format('Y-m-d');

        $candidate_date = $this->secondTimeProcessing($createdShipment[0]->getShipmentDate());
        $shipmentId = new ShipmentId(2);
        $createdShipment[] = new Shipment($shipmentId, $candidate_date[0]); // 初回発送から2週間後
        // NOTE: DB保存は不要という仕様だったため
        $createdShipmentDate[] = $createdShipment[1]->getShipmentDate()->format('Y-m-d');

        $shipmentId = new ShipmentId(3);
        $createdShipment[] = new Shipment($shipmentId, $candidate_date[1]); // 初回発送から4週間後
        // NOTE: DB保存は不要という仕様だったため
        $createdShipmentDate[] = $createdShipment[2]->getShipmentDate()->format('Y-m-d');

        return new ShipmentCreateResponse($createdShipmentDate);
    }

    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * @param DateTime
     * @return DateTime[]
     */
    public function secondTimeProcessing($shipment_date)
    {
        // 2回目以降の処理
        $shipmentdate = array();
        // 2週間後の平日を算出
        $shipmentdate[] = $this->getWeekdayAfterWeeks($shipment_date, 2);
        // 4週間後の平日を算出
        $shipmentdate[] = $this->getWeekdayAfterWeeks($shipment_date, 4);
        
        return $shipmentdate;
    }
    
    /**
    * @param DateTime,Integer
    * @return DateTime
    */
    public function getWeekdayAfterWeeks($shipmentdate, $number)
    {
        $weekdays = array();
        $weekdays[] = $this->getNextWeekday($shipmentdate, $number, 'monday');
        $weekdays[] = $this->getNextWeekday($shipmentdate, $number, 'tuesday');
        $weekdays[] = $this->getNextWeekday($shipmentdate, $number, 'wednesday');
        $weekdays[] = $this->getNextWeekday($shipmentdate, $number, 'thursday');
        $weekdays[] = $this->getNextWeekday($shipmentdate, $number, 'friday');

        // FIXME:シャッフルされているかのテストができない
        shuffle($weekdays);

        return $weekdays[0];
    }
    
    /**
     * @param DateTime,Integer,String 
     * @return DateTime
     */
    public function getNextWeekday($shipmentdate, $number, $weekday)
    {
        $tmp = clone $shipmentdate;
        $tmp->modify("+$number weeks");
        $tmp->modify($weekday . " this week");
        return $tmp;
    }

    /**
     * @param DateTime
     * @return DateTime
     */
    public function firstTimeProcessing($settlementdate)
    {
        $nextDay = $settlementdate->modify('+1 day');
        if ($this->isSaturdayOrSunday($nextDay)) {
            $shipmentdate = $settlementdate->modify('next monday');
        } else {
            $shipmentdate = $nextDay;
        }
        
        return $shipmentdate;
    }

    public function isSaturdayOrSunday($date)
    {
        $year = $date->format('Y');
        $month = $date->format('n');
        $day = $date->format('j');
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        $tmp = date('w', $timestamp);
        // 土日かどうか
        if ($tmp == 0 || $tmp == 6) {
            return true;
        }
        return false;
    }
}
