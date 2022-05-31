<?php
/**
 * YooKassa driver for Omnipay payment processing library
 *
 * @link      https://github.com/igor-tv/omnipay-yookassa
 * @package   omnipay-yookassa
 * @license   MIT
 * @copyright Copyright (c) 2021, Igor Tverdokhleb, igor-tv@mail.ru
 */

namespace Omnipay\YooKassa\Tests;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\YooKassa\Gateway;

class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    public $gateway;

    private $shopId         = '54401';
    private $secretKey      = 'test_Fh8hUAVVBGUGbjmlzba6TB0iyUbos_lueTHE-axOwM0';

    private $transactionId  = 'sadf2345asf';
    private $amount         = '12.46';
    private $currency       = 'USD';
    private $description    = 'Test completePurchase description';
    private $capture        = false;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setShopId($this->shopId);
        $this->gateway->setSecret($this->secretKey);
    }

    public function testGateway()
    {
        $this->assertSame($this->shopId,     $this->gateway->getShopId());
        $this->assertSame($this->secretKey,  $this->gateway->getSecret());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase([
            'transactionId' => $this->transactionId,
            'amount'        => $this->amount,
            'currency'      => $this->currency,
            'description'   => $this->description,
            'capture'       => $this->capture,
        ]);

        $this->assertSame($this->transactionId, $request->getTransactionId());
        $this->assertSame($this->description,   $request->getDescription());
        $this->assertSame($this->currency,      $request->getCurrency());
        $this->assertSame($this->capture,       $request->getCapture());
        $this->assertSame($this->amount,        $request->getAmount());
    }

    public function testCaptureParameters()
    {
        $this->markTestSkipped('Capture not supported');
    }

    public function testPurchaseParameters()
    {
        $this->markTestSkipped('Purchase not supported');
    }

    public function testDefaultParametersHaveMatchingMethods()
    {
        $this->markTestSkipped('Default parameters not supported');
    }
}
