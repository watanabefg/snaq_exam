<?php
namespace Tests\Unit\UseCases;

use DateTime;
use PHPUnit\Framework\TestCase;
use packages\UseCases\Settlement\Create\SettlementCreateRequest;
use packages\UseCases\Settlement\Create\SettlementCreateResponse;
use packages\UseCases\Settlement\Create\SettlementCreateInteractor;

class SettlementCreateInteractorTest extends TestCase
{

    public function testValidateDate()
    {
        $expected = true;
        $date = "2020/6/13";
        $format = "Y/n/j";
        $interactor = new SettlementCreateInteractor();
        $result = $interactor->validateDate($date, $format);
        $this->assertEquals($expected, $result);
    }

    public function testValidateDate2()
    {
        $expected = false;
        $date = "あ";
        $format = "Y/n/j";
        $interactor = new SettlementCreateInteractor();
        $result = $interactor->validateDate($date, $format);
        $this->assertEquals($expected, $result);
    }

    public function testValidateDate3()
    {
        $expected = false;
        $date = "あああ/ああ/いい";
        $format = "Y/n/j";
        $interactor = new SettlementCreateInteractor();
        $result = $interactor->validateDate($date, $format);
        $this->assertEquals($expected, $result);
    }

    public function testValidateDate4()
    {
        $expected = false;
        $date = "2020/02/02";
        $format = "Y/n/j";
        $interactor = new SettlementCreateInteractor();
        $result = $interactor->validateDate($date, $format);
        var_dump($result);
        $this->assertEquals($expected, $result);
    }

    public function testValidateDate5()
    {
        $expected = false;
        $date = "2020/2/30"; // 存在しない日
        $format = "Y/n/j";
        $interactor = new SettlementCreateInteractor();
        $result = $interactor->validateDate($date, $format);
        $this->assertEquals($expected, $result);
    }
    
    public function testDeriveSettlementDate()
    {
        // 発送日の前週月曜日が決済日 (ex. 5/11~5/15発送は5/4決済)
        $expected = new DateTime("2020/5/4");
        $interactor = new SettlementCreateInteractor();
        $shipment_date = new DateTime("2020/5/11");
        $result = $interactor->deriveSettlementDate($shipment_date);
        $this->assertEquals($expected, $result);
    }

    public function testValidHandle()
    {
        // 発送日の前週月曜日が決済日 (ex. 5/11~5/15発送は5/4決済)
        $expected = new SettlementCreateResponse("2020-05-04");
        $shipment_date = "2020/5/13";
        $request = new SettlementCreateRequest($shipment_date);
        $interactor = new SettlementCreateInteractor();
        $result = $interactor->handle($request);
        $this->assertEquals($expected, $result);
    }

    public function testInValidHandle()
    {
        // 発送日の前週月曜日が決済日 (ex. 5/11~5/15発送は5/4決済)
        $expected = new SettlementCreateResponse('発送日をY/n/j形式で入力してください');
        $shipment_date = "2020/あ";
        $request = new SettlementCreateRequest($shipment_date);
        $interactor = new SettlementCreateInteractor();
        $result = $interactor->handle($request);
        $this->assertEquals($expected, $result);
    }

}
