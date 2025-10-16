<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function getUserOrders($userId, $status = null)
    {
        $query = Order::with('items.product')->where('user_id', $userId);
        if ($status) {
            $query->where('status', $status);
        }
        return $query->paginate(10);
    }

    public function findUserOrder($userId, $id)
    {
        return Order::with('items.product', 'payments')
            ->where('user_id', $userId)
            ->findOrFail($id);
    }

    public function createOrder($userId, array $items)
    {
        return DB::transaction(function () use ($userId, $items) {
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending',
                'total' => 0
            ]);

            $total = 0;

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal
                ]);
            }

            $order->update(['total' => $total, 'status' => 'confirmed']);

            return $order->load('items.product');
        });
    }

    public function updateOrder(Order $order, array $validated)
    {
        return DB::transaction(function () use ($order, $validated) {
            $total = 0;

            if (isset($validated['items'])) {
                $order->items()->delete();

                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $subtotal = $product->price * $item['quantity'];
                    $total += $subtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $subtotal
                    ]);
                }
            } else {
                $total = $order->total;
            }

            if (isset($validated['status'])) {
                $order->status = $validated['status'];
            }

            $order->total = $total;
            $order->save();

            return $order->load('items.product');
        });
    }

    public function deleteOrder(Order $order)
    {
        if ($order->payments()->exists()) {
            return ['error' => 'Order cannot be deleted because it has payments'];
        }

        $order->delete();
        return ['message' => 'Order deleted successfully'];
    }
}
