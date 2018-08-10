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
     * Test that true does in fact equal true
     */
    public function testAmountCanBeSet()
    {
        $amount = 1;
        $this->payment->amount($amount);

        $this->assertEquals($amount, $this->payment->getAmount());
    }

    /**
     * Test that true does in fact equal true
     */
    public function testFromCanBeSet()
    {
        $from = '0248888888';
        $this->payment->from($from);
        
        $this->assertEquals($from, $this->payment->getFrom());
    }

    /**
     * Test that true does in fact equal true
     */
    public function testToCanBeSet()
    {
        $to = '0248888888';
        $this->payment->to($to);
        
        $this->assertEquals($to, $this->payment->getTo());
    }

    /**
     * Test that true does in fact equal true
     */
    public function testGettingNetworkOfSender()
    {
        $paymentFlow = 'AIRTEL_TO_MTN';
        $this->payment->transfer($paymentFlow);
        
        $this->assertEquals('airtel', $this->payment->getPayeeNetwork());
    }

    /**
     * Test that true does in fact equal true
     */
    public function testFlowOfTransaction()
    {
        $paymentFlow = 'AIRTEL_TO_MTN';
        $this->payment->transfer($paymentFlow);
        
        $this->assertEquals('ratm', $this->payment->getFlow());
    }

    /**
     * Test that true does in fact equal true
     */
    public function testMazzumaPaymentHasKeyAttribute()
    {
        $this->assertClassHasAttribute('key', MazzumaPayment::class);
    }

    /**
     * Test that true does in fact equal true
     */
    public function testMazzumaPaymentHasApiAttribute()
    {
        $this->assertClassHasAttribute('api', MazzumaPayment::class);
    }

    /**
     * Test that true does in fact equal true
     */
    public function testMazzumaPaymentHasApiResponseAttribute()
    {
        $this->assertClassHasAttribute('apiResponse', MazzumaPayment::class);
    }
}
