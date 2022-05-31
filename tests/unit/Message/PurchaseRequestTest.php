<?php
/**
 * YooKassa driver for Omnipay payment processing library
 *
 * @link      https://github.com/igor-tv/omnipay-yookassa
 * @package   omnipay-yookassa
 * @license   MIT
 * @copyright Copyright (c) 2021, Igor Tverdokhleb, igor-tv@mail.ru
 */

namespace Omnipay\YooKassa\Tests\Message;

use Omnipay\YooKassa\Message\PurchaseRequest;
use Omnipay\YooKassa\Message\PurchaseResponse;

class PurchaseRequestTest extends TestCase
{
    /** @var \Omnipay\YooKassa\Message\PurchaseRequest */
    private $request;

    private $shopId         = '54401';
    private $secretKey      = 'test_Fh8hUAVVBGUGbjmlzba6TB0iyUbos_lueTHE-axOwM0';

    private $transactionId  = '5ce3cdb0d1437';
    private $amount         = '12.46';
    private $currency       = 'RUB';
    private $description    = 'Test completePurchase description';
    private $returnUrl      = 'https://www.foodstore.com/success';
    private $capture        = false;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'yooKassaClient'  => $this->buildYooKassaClient($this->shopId, $this->secretKey),
            'transactionId' => $this->transactionId,
            'amount'        => $this->amount,
            'currency'      => $this->currency,
            'description'   => $this->description,
            'returnUrl'     => $this->returnUrl,
            'capture'       => $this->capture,
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->amount, $data['amount']);
        $this->assertSame($this->currency, $data['currency']);
        $this->assertSame($this->description, $data['description']);
        $this->assertSame($this->returnUrl, $data['return_url']);
        $this->assertSame($this->capture, $data['capture']);
        $this->assertSame($this->transactionId, $data['transactionId']);
    }

    public function testSendData()
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub->method('sendRequest')->willReturn([
            [],
            $this->fixture('payment.pending'),
            ['http_code' => 200],
        ]);

        $this->getYooKassaClient($this->request)
             ->setApiClient($curlClientStub)
             ->setAuth($this->shopId, $this->secretKey);

        $response = $this->request->send();
        $this->assertInstanceOf(PurchaseResponse::class, $response);
    }
}
