<?php

namespace App\Services\Payments;

use App\Models\Order;

class PaypalPayment implements PaymentStrategyInterface
{
    public function process(Order $order): array
    {
        return [
            'status' => 'success',
            'message' => "Payment of {$order->total} via PayPal processed successfully."
        ];
    }
}
