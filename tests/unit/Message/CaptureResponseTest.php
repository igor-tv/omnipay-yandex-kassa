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

use Omnipay\YooKassa\Message\CaptureRequest;
use Omnipay\YooKassa\Message\DetailsResponse;
use Omnipay\YooKassa\Message\IncomingNotificationRequest;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CaptureResponseTest extends TestCase
{
    /** @var IncomingNotificationRequest */
    private $request;

    private $shopId               = '54401';
    private $secretKey            = 'test_Fh8hUAVVBGUGbjmlzba6TB0iyUbos_lueTHE-axOwM0';
    private $transactionReference = '2475e163-000f-5000-9000-18030530d620';
    private $transactionId        = '5ce3cdb0d1436';
    private $amount               = '187.50';
    private $currency             = 'RUB';

    public function setUp(): void
    {
        parent::setUp();

        $httpRequest = new HttpRequest();
        $this->request = new CaptureRequest($this->getHttpClient(), $httpRequest);
        $this->request->initialize([
            'yooKassaClient' => $this->buildYooKassaClient($this->shopId, $this->secretKey),
            'shopId' => $this->shopId,
            'secret' => $this->secretKey,
            'transactionReference' => $this->transactionReference,
            'transactionId' => $this->transactionId,
            'amount' => $this->amount,
            'currency' => $this->currency,
        ]);
    }

    public function testSuccess(): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub->method('sendRequest')
                       ->willReturn([
                           [],
                           $this->fixture('payment.succeeded'),
                           ['http_code' => 200],
                       ]);

        $this->getYooKassaClient($this->request)
             ->setApiClient($curlClientStub)
             ->setAuth($this->shopId, $this->secretKey);

        /** @var DetailsResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(DetailsResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('5ce3cdb0d1436', $response->getTransactionId());
        $this->assertSame('2475e163-000f-5000-9000-18030530d620', $response->getTransactionReference());
        $this->assertSame('187.50', $response->getAmount());
        $this->assertSame('RUB', $response->getCurrency());
        $this->assertSame('2019-05-21T10:09:54+00:00', $response->getPaymentDate()->format(\DATE_ATOM));
        $this->assertSame('succeeded', $response->getState());
        $this->assertSame('Bank card *4444', $response->getPayer());
    }
}
