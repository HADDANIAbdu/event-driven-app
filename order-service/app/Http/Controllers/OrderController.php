<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class OrderController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'customer_id' => 'required|string',
            'items'         => 'required|array',
            'items.*.name'  => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.qty'   => 'required|integer',
            'address'       => 'required|string',
        ]);

        $total = collect($validated['items'])->sum(fn($item) => $item['price'] * $item['qty']);

        $orderToCreate = [
            'order_id' => uniqid('order_'),
            'customer_id'   => $validated['customer_id'],
            'restaurant_id' => $validated['restaurant_id'],
            'items'         => $validated['items'],
            'address'       => $validated['address'],
            'total'         => $total,
            'status'        => 'PLACED',
            'placed_at'     => now()->toISOString(),
        ];

        $order = Order::create($orderToCreate);

        Kafka::publishOn('order.placed')->withMessage(new Message(body: $order))->send();

        return response()->json([
            'message' => 'Order placed Successfully',
            'order' => $order,
        ], 200);
    }

    public function index(){
        return response()->json(Order::latest()->get());
    }

    public function show(string $orderId){
        $order = Order::where('order_id', $orderId)->firstOrFail();
        return response()->json($order);
    }
}
