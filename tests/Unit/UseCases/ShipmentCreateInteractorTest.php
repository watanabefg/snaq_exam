<?php
namespace Tests\Unit\UseCases;

use DateTime;
use PHPUnit\Framework\TestCase;
use packages\UseCases\Shipment\Create\ShipmentCreateRequest;
use packages\UseCases\Shipment\Create\ShipmentCreateResponse;
use packages\UseCases\Shipment\Create\ShipmentCreateInteractor;

class ShipmentCreateInteractorTest extends TestCase
{
    public function testValidateDate()
    {
        $expected = true;
        $date = "2020/6/13";
        $format = "Y/n/j";
        $interactor = new ShipmentCreateInteractor();
        $result = $interactor->validateDate($date, $format);
        $this->assertEquals($expected, $result);
    }

    public function testValidateDate2()
    {
        $expected = false;
        $date = "あ";
        $format = "Y/n/j";
        $interactor = new ShipmentCreateInteractor();
        $result = $interactor->validateDate($date, $format);
        $this->assertEquals($expected, $result);
    }

    public function testValidateDate3()
    {
        $expected = false;
        $date = "あああ/ああ/いい";
        $format = "Y/n/j";
        $interactor = new ShipmentCreateInteractor();
        $result = $interactor->validateDate($date, $format);
        $this->assertEquals($expected, $result);
    }

    public function testValidateDate4()
    {
        $expected = false;
        $date = "2020/02/02";
        $format = "Y/n/j";
        $interactor = new ShipmentCreateInteractor();
        $result = $interactor->validateDate($date, $format);
        $this->assertEquals($expected, $result);
    }

    public function testValidateDate5()
    {
        $expected = false;
        $date = "2020/2/30";
        $format = "Y/n/j";
        $interactor = new ShipmentCreateInteractor();
        $result = $interactor->validateDate($date, $format);
        $this->assertEquals($expected, $result);
    }

    public function testValidFirstTimeProcessing()
    {
        // 次の営業日であること
        $interactor = new ShipmentCreateInteractor();
        $settlement_date = new DateTime("2020/6/12");
        $result = $interactor->firstTimeProcessing($settlement_date);
        $expected = new DateTime("2020/6/15");
        $this->assertEquals($expected, $result);
    }

    public function testValidFirstTimeProcessing2()
    {
        // 次の営業日であること
        $interactor = new ShipmentCreateInteractor();
        $settlement_date = new DateTime("2020/6/11");
        $result = $interactor->firstTimeProcessing($settlement_date);
        $expected = new DateTime("2020/6/12");
        $this->assertEquals($expected, $result);
    }

    public function testIsSaturdayOrSunday()
    {
        $saturday = new DateTime("2020/6/13");
        $interactor = new ShipmentCreateInteractor();
        $result = $interactor->isSaturdayOrSunday($saturday);
        $this->assertTrue($result);
    }

    public function testIsSaturdayOrSunday2()
    {
        $sunday = new DateTime("2020/6/14");
        $interactor = new ShipmentCreateInteractor();
        $result = $interactor->isSaturdayOrSunday($sunday);
        $this->assertTrue($result);
    }

    public function testIsSaturdayOrSunday3()
    {
        $monday = new DateTime("2020/6/15");
        $interactor = new ShipmentCreateInteractor();
        $result = $interactor->isSaturdayOrSunday($monday);
        $this->assertFalse($result);
    }

    public function testGetNextWeekday()
    {
        $expected = new DateTime("2020/6/8");
        $interactor = new ShipmentCreateInteractor();
        $shipment_date = new DateTime("2020/6/1"); // 月曜日
        $number = 1;
        $weekday = 'monday';
        $result = $interactor->getNextWeekday($shipment_date, $number, $weekday);
        $this->assertEquals($expected, $result);
    }

    public function testGetNextWeekday2()
    {
        $expected = new DateTime("2020/6/15");
        $interactor = new ShipmentCreateInteractor();
        $shipment_date = new DateTime("2020/6/1"); // 月曜日
        $number = 2;
        $weekday = 'monday';
        $result = $interactor->getNextWeekday($shipment_date, $number, $weekday);
        $this->assertEquals($expected, $result);
    }

    public function testGetNextWeekday3()
    {
        $expected = new DateTime("2020/6/16");
        $interactor = new ShipmentCreateInteractor();
        $shipment_date = new DateTime("2020/6/1"); // 月曜日
        $number = 2;
        $weekday = 'tuesday';
        $result = $interactor->getNextWeekday($shipment_date, $number, $weekday);
        $this->assertEquals($expected, $result);
    }

    public function testGetGetWeekdayAfterWeeks()
    {
        $interactor = new ShipmentCreateInteractor();
        $shipment_date = new DateTime("2020/6/1");
        $number = 1;
        $result = $interactor->getWeekdayAfterWeeks($shipment_date, $number);
        $candidate_dates = array(
            new DateTime("2020/6/8"),
            new DateTime("2020/6/9"),
            new DateTime("2020/6/10"),
            new DateTime("2020/6/11"),
            new DateTime("2020/6/12")
        );

        $this->assertTrue(in_array($result, $candidate_dates));
    }

    public function testGetGetWeekdayAfterWeeks2()
    {
        $interactor = new ShipmentCreateInteractor();
        $shipment_date = new DateTime("2020/6/1");
        $number = 2;
        $result = $interactor->getWeekdayAfterWeeks($shipment_date, $number);
        $candidate_dates = array(
            new DateTime("2020/6/15"),
            new DateTime("2020/6/16"),
            new DateTime("2020/6/17"),
            new DateTime("2020/6/18"),
            new DateTime("2020/6/19")
        );

        $this->assertTrue(in_array($result, $candidate_dates));
    }

    public function testSecondTimeProcessing()
    {
        $interactor = new ShipmentCreateInteractor();
        $shipment_date_1st = new DateTime("2020/6/1");
        $result = $interactor->secondTimeProcessing($shipment_date_1st);
        $candidate_dates2 = array(
            new DateTime("2020/6/15"),
            new DateTime("2020/6/16"),
            new DateTime("2020/6/17"),
            new DateTime("2020/6/18"),
            new DateTime("2020/6/19")
        );
        $candidate_dates4 = array(
            new DateTime("2020/6/29"),
            new DateTime("2020/6/30"),
            new DateTime("2020/7/1"),
            new DateTime("2020/7/2"),
            new DateTime("2020/7/3")
        );

        $this->assertEquals(2, count($result));
        $this->assertTrue(in_array($result[0], $candidate_dates2));
        $this->assertTrue(in_array($result[1], $candidate_dates4));
    }

    public function testInValidHandle()
    {
        $expected = new ShipmentCreateResponse(['決済日をY/n/j形式で入力してください']);
        $settlement_date = "あ";
        $request = new ShipmentCreateRequest($settlement_date);
        $interactor = new ShipmentCreateInteractor();
        $result = $interactor->handle($request);
        $this->assertEquals($expected, $result);
    }

    public function testValidHandle()
    {
        $expected = '2020-06-15';
        $candidate_dates2 = array(
            "2020-06-29",
            "2020-06-30",
            "2020-07-01",
            "2020-07-02",
            "2020-07-03"
        );
        $candidate_dates4 = array(
            "2020-07-13",
            "2020-07-14",
            "2020-07-15",
            "2020-07-16",
            "2020-07-17"
        );

        $settlement_date = "2020/6/12";
        $request = new ShipmentCreateRequest($settlement_date);
        $interactor = new ShipmentCreateInteractor();
        $result = $interactor->handle($request);
        
        $this->assertEquals($expected, $result->getCreatedShipmentDate()[0]);
        $this->assertTrue(in_array($result->getCreatedShipmentDate()[1], $candidate_dates2));
        $this->assertTrue(in_array($result->getCreatedShipmentDate()[2], $candidate_dates4));
    }
}
