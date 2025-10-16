<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Payment;

class PaymentRepository
{
    public function getAllPaginated($perPage = 10)
    {
        return Payment::with('order')->paginate($perPage);
    }

    public function create(Order $order, string $method, string $status)
    {
        return Payment::create([
            'order_id' => $order->id,
            'method' => $method,
            'status' => $status,
        ]);
    }

    public function findOrder($id)
    {
        return Order::findOrFail($id);
    }
    
    public function updateOrderStatus(Order $order, string $status): void
    {
        $order->update(['status' => $status]);
    }
}
