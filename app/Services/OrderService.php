<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\Order;

class OrderService
{
    protected $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listOrders($userId, $status = null)
    {
        return $this->repository->getUserOrders($userId, $status);
    }

    public function showOrder($userId, $id)
    {
        return $this->repository->findUserOrder($userId, $id);
    }

    public function createOrder($userId, array $items)
    {
        return $this->repository->createOrder($userId, $items);
    }

    public function updateOrder(Order $order, array $validated)
    {
        return $this->repository->updateOrder($order, $validated);
    }

    public function deleteOrder(Order $order)
    {
        return $this->repository->deleteOrder($order);
    }
}
