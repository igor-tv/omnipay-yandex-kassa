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

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class IncomingNotificationResponse extends AbstractResponse
{
    /**
     * @return RequestInterface|AbstractRequest
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    /**
     * CompletePurchaseResponse constructor.
     * @param RequestInterface $request
     * @param array $data
     * @throws \Exception
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
    }

    public function getTransactionId()
    {
        return $this->data['object']['metadata']['transactionId'] ?? null;
    }

    public function getTransactionReference()
    {
        return $this->data['object']['id'] ?? null;
    }

    public function isSuccessful()
    {
        return $this->getTransactionReference() !== null;
    }
}
