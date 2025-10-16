<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentManager;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentManager $service;

    public function __construct(PaymentManager $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $payments = $this->service->listPayments();
        return PaymentResource::collection($payments);
    }


    public function store(StorePaymentRequest $request, $orderId)
    {
        $result = $this->service->processPayment($orderId, $request->validated()['method']);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json([
            'data' => new PaymentResource($result['payment']),
            'message' => $result['message'],
        ], 201);
    }
}
