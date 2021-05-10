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
use YooKassaCheckout\Model\PaymentStatus;
use YooKassaCheckout\Request\Payments\Payment\CreateCaptureResponse;

/**
 * Class CaptureResponse.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 * @property CreateCaptureResponse $data
 */
class CaptureResponse extends DetailsResponse
{
    protected function ensureResponseIsValid(): void
    {
        parent::ensureResponseIsValid();

        if ($this->getState() !== PaymentStatus::SUCCEEDED) {
            throw new InvalidResponseException(sprintf('Failed to capture payment "%s"', $this->getTransactionReference()));
        }
    }
}
