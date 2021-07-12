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
use Omnipay\Common\Message\ResponseInterface;

/**
 * Class DetailsRequest.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class DetailsRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        return [];
    }

    /**
     * Send the request with specified data.
     *
     * @param  mixed $data The data to send
     * @throws InvalidResponseException
     * @return DetailsResponse|ResponseInterface
     */
    public function sendData($data): ResponseInterface
    {
        try {
            $response = $this->client->getPaymentInfo($this->getTransactionReference());

            return new DetailsResponse($this, $response);
        } catch (\Throwable $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
