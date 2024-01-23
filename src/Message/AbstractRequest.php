<?php
/**
 * YooKassa driver for Omnipay payment processing library
 *
 * @link      https://github.com/igor-tv/omnipay-yookassa
 * @package   omnipay-yookassa
 * @license   MIT
 * @copyright Copyright (c) 2021, Igor Tverdokhleb, igor-tv@mail.ru
 */

namespace Omnipay\YooKassa\Message;

use YooKassa\Client;

/**
 * Class AbstractRequest.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * @var Client
     */
    protected $client;

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

    public function getCapture()
    {
        return $this->getParameter('capture');
    }
    
    public function setCapture($value)
    {
        return $this->setParameter('capture', $value);
    }

    public function getReceipt()
    {
        return $this->getParameter('receipt');
    }
    
    public function setReceipt($value)
    {
        return $this->setParameter('receipt', $value);
    }

    public function getPaymentMethodData()
    {
        return $this->getParameter('payment_method_data');
    }
    
    public function setPaymentMethodData($value)
    {
        return $this->setParameter('payment_method_data', $value);
    }

    public function setYooKassaClient(Client $client): void
    {
        $this->client = $client;
    }
}
