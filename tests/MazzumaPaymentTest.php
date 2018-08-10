<?php

namespace BaffourAdu\mazzuma;

use \BaffourAdu\Mazzuma\MazzumaPayment;

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
    public function testAmountIsSet()
    {
        $amount = 1;
        $this->payment->amount($amount);

        $this->assertEquals($amount, $this->payment->getAmount());
    }

    /**
     * Test that true does in fact equal true
     */
    public function testFromIsSet()
    {
        $from = "0248888888";
        $this->payment->from($from);
        
        $this->assertEquals($from, $this->payment->getFrom());
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
    public function testMazzumaPaymentHasPayeeNetworkAttribute()
    {
        $this->assertClassHasAttribute('api', MazzumaPayment::class);
    }
}
