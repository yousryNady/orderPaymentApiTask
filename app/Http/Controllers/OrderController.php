<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $orders = $this->service->listOrders(auth()->id(), $request->get('status'));
        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        $order = $this->service->showOrder(auth()->id(), $id);
        return new OrderResource($order);
    }

    public function store(CreateOrderRequest $request)
    {
        $order = $this->service->createOrder(auth()->id(), $request->validated()['items']);
        return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        $order = Order::with('payments')->where('user_id', auth()->id())->findOrFail($id);

        if ($order->payments()->exists()) {
            return response()->json(['error' => 'Order cannot be updated because it has payments'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $updated = $this->service->updateOrder($order, $request->validated());
        return new OrderResource($updated);
    }

    public function destroy($id)
    {
        $order = Order::with('payments')->where('user_id', auth()->id())->findOrFail($id);
        $result = $this->service->deleteOrder($order);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json(['message' => $result['message']]);
    }
}
