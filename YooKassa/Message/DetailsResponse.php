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

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use YooKassaCheckout\Model\PaymentInterface;

/**
 * Class DetailsResponse.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 * @property PaymentInterface $data
 */
class DetailsResponse extends AbstractResponse
{
    /**
     * @return RequestInterface|DetailsRequest
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    public function __construct(RequestInterface $request, PaymentInterface $payment)
    {
        parent::__construct($request, $payment);

        $this->ensureResponseIsValid();
    }

    protected function ensureResponseIsValid(): void
    {
        if ($this->getTransactionId() === null) {
            throw new InvalidResponseException(sprintf(
                'Transaction ID is missing in payment "%s"',
                $this->getTransactionReference()
            ));
        }
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return $this->data->paid;
    }

    public function getAmount(): string
    {
        return $this->data->getAmount()->getValue();
    }

    public function getCurrency(): string
    {
        return $this->data->getAmount()->getCurrency();
    }

    public function getPaymentDate(): \DateTime
    {
        return $this->data->getCreatedAt();
    }

    public function getTransactionReference(): string
    {
        return $this->data->getId();
    }

    public function getTransactionId(): ?string
    {
        return $this->data->getMetadata()['transactionId'] ?? null;
    }

    public function getState(): string
    {
        return $this->data->getStatus();
    }

    public function getPayer(): string
    {
        $method = $this->data->getPaymentMethod();

        return $method->getTitle() ?: $method->getType() ?: '';
    }
}
