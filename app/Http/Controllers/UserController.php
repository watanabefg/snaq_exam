<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
  /**
   * @var Datetime $settlementdate
   * @return Datetime
   */
  public function setShipmentdate($settlementdate) {
    $user = User::find(1);

    if ($user->repeat > 0) {
      // 2回目以降の処理
      $shipmentdate = array();
      // 2週間後の平日を算出
      $shipmentdate[] = $this->getWeekdayAfterWeeks($user->shipmentdate, 2);
      // 4週間後の平日を算出
      $shipmentdate[] = $this->getWeekdayAfterWeeks($user->shipmentdate, 4);

    }else{
      // 決済日から1日後が土日か？
      $nextDay = $settlementdate->modify('+1 day');
      if (isSaterdayOrSunday($nextDay)) {
          $shipmentdate = $settlementdate->modify('next monday');
      }else{
          $shipmentdate = $nextDay;
      }
    }

    // TODO:DBに保存

    return $shipmentdate;
  }
   /**
   * @var Datetime $shipmentdate Integer $number
   * @return Datetime
   */
  public function getWeekdayAfterWeeks($shipmentdate, $number) {
    $weekdays = array();
    $weekdays[] = $shipmentdate->modify('next monday')->modify('next monday');
    $weekdays[] = $shipmentdate->modify('next tuesday')->modify('next tuesday');
    $weekdays[] = $shipmentdate->modify('next wednesday')->modify('next wednesday');
    $weekdays[] = $shipmentdate->modify('next thursday')->modify('next thursday');
    $weekdays[] = $shipmentdate->modify('next friday')->modify('next friday');

    shuffle($weekdays);

    return $weekdays[0];
  }

  public function isSaterdayOrSunday($date) {
      $year = date('Y', strtotime($date));
      $month = date('n', strtotime($date));
      $day = date('j', strtotime($date));
      $timestamp = mktime(0, 0, 0, $month, $day, $year);
      $tmp = date('w', $timestamp);
      // 土日かどうか
      if ($tmp == 0 || $tmp == 6) {
        return true;
      }
      return false;
  }
    
}
