<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function pay(Order $order)
    {
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'This order cannot be paid because it is not pending.'
            ], 400);
        }

        try {
            DB::transaction(function () use ($order) {
                $order->update(['status' => 'paid']);

                $user = User::findOrFail($order->user_id);

                $pointsToAdd = $order->total_price >= 100
                    ? $order->total_price + 10
                    : $order->total_price;

                $user->increment('points', $pointsToAdd);
            });

            $order->refresh();
            $user = User::find($order->user_id);

            return response()->json([
                'message' => 'Payment processed successfully.',
                'order' => $order,
                'user_points' => $user->points
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to process payment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}