<?php
/**
 * YooKassa driver for Omnipay payment processing library
 *
 * @link      https://github.com/igor-tv/omnipay-yookassa
 * @package   omnipay-yookassa
 * @license   MIT
 * @copyright Copyright (c) 2021, Igor Tverdokhleb, igor-tv@mail.ru
 */

namespace Omnipay\YooKassa;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\YooKassa\Message\CaptureRequest;
use Omnipay\YooKassa\Message\CaptureResponse;
use Omnipay\YooKassa\Message\DetailsRequest;
use Omnipay\YooKassa\Message\DetailsResponse;
use Omnipay\YooKassa\Message\IncomingNotificationRequest;
use Omnipay\YooKassa\Message\PurchaseRequest;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use YooKassa\Client;

/**
 * Class Gateway.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Gateway extends AbstractGateway
{
    /** @var Client|null */
    private $yooKassaClient;

    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        parent::__construct($httpClient, $httpRequest);
    }

    protected function getYooKassaClient(): Client
    {
        if ($this->yooKassaClient === null) {
            $this->yooKassaClient = new Client();
            $this->yooKassaClient->setAuth($this->getShopId(), $this->getSecret());
        }

        return $this->yooKassaClient;
    }

    public function getName()
    {
        return 'YooKassa';
    }

    public function getShopId()
    {
        return $this->getParameter('shopId');
    }

    public function setShopId($value)
    {
        return $this->setParameter('shopId', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    /**
     * @param array $parameters
     * @return PurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $this->injectYooKassaClient($parameters));
    }

    /**
     * @param array $parameters
     * @return CaptureResponse|\Omnipay\Common\Message\AbstractRequest
     */
    public function capture(array $parameters = [])
    {
        return $this->createRequest(CaptureRequest::class, $this->injectYooKassaClient($parameters));
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|DetailsRequest
     */
    public function details(array $parameters = [])
    {
        return $this->createRequest(DetailsRequest::class, $this->injectYooKassaClient($parameters));
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|DetailsResponse
     */
    public function notification(array $parameters = [])
    {
        return $this->createRequest(IncomingNotificationRequest::class, $this->injectYooKassaClient($parameters));
    }

    private function injectYooKassaClient(array $parameters): array
    {
        $parameters['yooKassaClient'] = $this->getYooKassaClient();

        return $parameters;
    }
}
