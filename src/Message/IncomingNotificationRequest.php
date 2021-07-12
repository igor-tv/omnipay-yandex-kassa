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
 * Class IncomingNotificationRequest.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 * @method IncomingNotificationResponse send()
 */
class IncomingNotificationRequest extends AbstractRequest
{
    public function getData()
    {
        $body = $this->httpRequest->getContent();

        return json_decode($body, true);
    }

    /**
     * Send the request with specified data.
     *
     * @param  mixed $data The data to send
     * @throws InvalidResponseException
     * @return ResponseInterface
     */
    public function sendData($data): ResponseInterface
    {
        try {
            return new IncomingNotificationResponse($this, $data);
        } catch (\Throwable $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
