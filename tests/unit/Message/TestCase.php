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

use Omnipay\YooKassa\Message\AbstractRequest;
use YooKassa\Client;

class TestCase extends \Omnipay\Tests\TestCase
{
    protected function buildYooKassaClient(string $shopId, string $secretKey): Client
    {
        $client = new Client();
        $client->setAuth($shopId, $secretKey);

        return $client;
    }

    protected function getCurlClientStub()
    {
        $clientStub = $this->getMockBuilder(Client\CurlClient::class)
                           ->onlyMethods(['sendRequest'])
                           ->getMock();

        return $clientStub;
    }

    protected function getYooKassaClient(AbstractRequest $request): Client
    {
        $clientReflection = (new \ReflectionObject($request))->getProperty('client');
        $clientReflection->setAccessible(true);

        return $clientReflection->getValue($request);
    }

    protected function fixture(string $name): string
    {
        return file_get_contents(__DIR__ . '/fixture/' . $name . '.json');
    }
}
