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

use Omnipay\Common\Exception\InvalidRequestException;
use Throwable;

/**
 * Class PurchaseRequest.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency', 'returnUrl', 'transactionId', 'description', 'capture');

        return [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'description' => $this->getDescription(),
            'return_url' => $this->getReturnUrl(),
            'transactionId' => $this->getTransactionId(),
            'capture' => $this->getCapture(),
            'receipt' => $this->getReceipt(),
            'payment_method_data' => $this->getPaymentMethodData(),
            'refundable' => true,
        ];
    }

    public function sendData($data)
    {
        try {
            $options = [
                'amount' => [
                    'value' => $data['amount'],
                    'currency' => $data['currency'],
                ],
                'receipt' => $data['receipt'],
                'description' => $data['description'],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => $data['return_url'],
                ],
                'capture' => $data['capture'],
                'metadata' => [
                    'transactionId' => $data['transactionId'],
                ],
            ];

            if(!empty($data['receipt'])) {
                $options['receipt'] = $data['receipt'];
            }

            if(!empty($data['payment_method_data'])) {
                $options['payment_method_data'] = $data['payment_method_data'];
            }


            $paymentResponse = $this->client->createPayment($options, $this->makeIdempotencyKey());

            return $this->response = new PurchaseResponse($this, $paymentResponse);
        } catch (Throwable $e) {
            throw new InvalidRequestException('Failed to request purchase: ' . $e->getMessage(), 0, $e);
        }
    }

    private function makeIdempotencyKey(): string
    {
        return md5(
            implode(',',
                ['create', json_encode($this->getData())]
            )
        );
    }
}
