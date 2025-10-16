<?php

namespace App\Services;

use App\Repositories\PaymentRepository;
use App\Services\Payments\PaymentStrategyInterface;
use App\Services\Payments\CreditCardPayment;
use App\Services\Payments\PaypalPayment;
use App\Models\Order;

class PaymentManager
{
    protected PaymentRepository $repository;

    public function __construct(PaymentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function processPayment(int $orderId, string $method)
    {
        $order = $this->repository->findOrder($orderId);

        if ($order->status !== 'confirmed') {
            return ['error' => 'Only confirmed orders can be paid'];
        }

        $strategy = match ($method) {
            'credit_card' => new CreditCardPayment(),
            'paypal' => new PaypalPayment(),
            default => throw new \Exception("Unsupported payment method: $method"),
        };

        $result = $strategy->process($order);

        if ($result['status'] === 'success') {
            $this->repository->updateOrderStatus($order, 'paid');
        } else {
            $this->repository->updateOrderStatus($order, 'failed');
        }
        $payment = $this->repository->create($order, $method, $result['status']);

        return [
            'payment' => $payment,
            'message' => $result['message'],
        ];
    }

    public function listPayments($perPage = 10)
    {
        return $this->repository->getAllPaginated($perPage);
    }
}
