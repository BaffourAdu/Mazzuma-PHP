<?php

namespace BaffourAdu\mazzuma;

use \BaffourAdu\Mazzuma\MazzumaPayment;
use BaffourAdu\Mazzuma\Exception\AmountValidateException;
use BaffourAdu\Mazzuma\Exception\TelephoneValidateException;

class MazzumaPaymentTest extends \PHPUnit\Framework\TestCase
{
    protected $payment;

    public function setUp()
    {
        $this->payment = new MazzumaPayment("xxxxxxxx");
    }

    /**
     * @test
     */
    public function testAmountCanBeSet()
    {
        $amount = 1;
        $this->payment->amount($amount);

        $this->assertEquals($amount, $this->payment->getAmount());
    }

    /**
     * @test
     */
    public function testFromCanBeSet()
    {
        $from = '0248888888';
        $this->payment->from($from);
        
        $this->assertEquals($from, $this->payment->getFrom());
    }

    /**
     * @test
     */
    public function testToCanBeSet()
    {
        $to = '0248888888';
        $this->payment->to($to);
        
        $this->assertEquals($to, $this->payment->getTo());
    }

    /**
     * @test
     */
    public function testGettingNetworkOfSender()
    {
        $paymentFlow = 'AIRTEL_TO_MTN';
        $this->payment->transfer($paymentFlow);
        
        $this->assertEquals('airtel', $this->payment->getPayeeNetwork());
    }

    /**
     * @test
     */
    public function testFlowOfTransaction()
    {
        $paymentFlow = 'AIRTEL_TO_MTN';
        $this->payment->transfer($paymentFlow);
        
        $this->assertEquals('ratm', $this->payment->getFlow());
    }

    /**
     * @test
     */
    public function testMazzumaPaymentHasKeyAttribute()
    {
        $this->assertClassHasAttribute('key', MazzumaPayment::class);
    }

    /**
     * @test
     */
    public function testMazzumaPaymentHasApiAttribute()
    {
        $this->assertClassHasAttribute('api', MazzumaPayment::class);
    }

    /**
     * @test
     */
    public function testMazzumaPaymentHasApiResponseAttribute()
    {
        $this->assertClassHasAttribute('apiResponse', MazzumaPayment::class);
    }
}
