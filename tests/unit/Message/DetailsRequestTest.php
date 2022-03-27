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

use Omnipay\YooKassa\Message\DetailsRequest;
use Omnipay\YooKassa\Message\DetailsResponse;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class DetailsRequestTest extends TestCase
{
    /** @var DetailsRequest */
    private $request;

    private $shopId                 = '54401';
    private $secretKey              = 'test_Fh8hUAVVBGUGbjmlzba6TB0iyUbos_lueTHE-axOwM0';
    private $transactionReference   = '2475e163-000f-5000-9000-18030530d620';

    public function setUp(): void
    {
        parent::setUp();

        $httpRequest = new HttpRequest();
        $this->request = new DetailsRequest($this->getHttpClient(), $httpRequest);
        $this->request->initialize([
            'yooKassaClient'  => $this->buildYooKassaClient($this->shopId, $this->secretKey),
            'shopId'        => $this->shopId,
            'secret'        => $this->secretKey,
            'transactionReference' => $this->transactionReference,
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertEmpty($data);
    }

    public function testSendData()
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub->method('sendRequest')
            ->willReturn([
                [],
                $this->fixture('payment.waiting_for_capture'),
                ['http_code' => 200],
            ]);

        $this->getYooKassaClient($this->request)
             ->setApiClient($curlClientStub)
             ->setAuth($this->shopId, $this->secretKey);

        $response = $this->request->sendData([]);
        $this->assertInstanceOf(DetailsResponse::class, $response);
    }
}
