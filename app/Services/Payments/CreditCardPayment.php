<?php

namespace App\Services\Payments;

use App\Models\Order;

class CreditCardPayment implements PaymentStrategyInterface
{
    public function process(Order $order): array
    {
        return [
            'status' => 'success',
            'message' => "Payment of {$order->total} via Credit Card processed successfully."
        ];
    }
}
