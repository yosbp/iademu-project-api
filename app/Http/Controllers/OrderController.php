<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['paymentOrder', 'provider', 'orderItems'])->get();

        // Get the total amount of each order
        foreach ($orders as $order) {
            $subTotal = 0;
            $totalAmount = 0;
            foreach ($order->orderItems as $item) {
                $subTotal += $item->total_amount;
            };
            $totalAmount = $subTotal + $order->tax - $order->exempt;

            $order->subtotal = $subTotal;
            $order->total = $totalAmount;
        }

        return response()->json($orders, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $order = Order::create([
            'provider_id' => $request->provider_id,
            'tax' => $request->tax,
            'exempt' => $request->exempt,
        ]);

        foreach ($request->items as $item) {
            $order->orderItems()->create([
                'item_name' => $item['item_name'],
                'item_quantity' => $item['item_quantity'],
                'item_amount' => $item['item_amount'],
                'total_amount' => $item['total_amount'],
            ]);
        }

        PaymentOrder::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'bank' => $request->bank,
            'account_number' => $request->account_number,
        ]);

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order = Order::with(['paymentOrder', 'provider'])->find($order->id);

        //Map order items to add id start from 1
        $order->orderItems = $order->orderItems->map(function ($item, $key) {
            $item->id = $key + 1;
            return $item;
        });

        // Get the total amount of each order
        $subTotal = 0;
        $totalAmount = 0;
        foreach ($order->orderItems as $item) {
            $subTotal += $item->total_amount;
        };
        $totalAmount = $subTotal + $order->tax - $order->exempt;

        $order->subtotal = $subTotal;
        $order->total = $totalAmount;

        return response()->json($order, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
