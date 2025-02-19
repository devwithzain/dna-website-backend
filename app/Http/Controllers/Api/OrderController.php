<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
   public function getAllOrders()
   {
      $orders = Order::with(['user', 'items.service'])->get();

      return response()->json($orders);
   }
   public function getAllOrdersForUser()
   {
      $user = Auth::user();
      if (!$user) {
         return response()->json(['message' => 'Unauthorized'], 401);
      }

      $orders = Order::where('user_id', $user->id)
         ->with(['items.service', 'user'])
         ->orderBy('created_at', 'desc')
         ->get();

      return response()->json($orders);
   }
   public function placeOrder(Request $request)
   {
      $request->validate([
         'user_id' => 'required|exists:users,id',
         'cart_items' => 'required|array|min:1',
         'cart_items.*.service_id' => 'required|exists:services,id',
         'cart_items.*.quantity' => 'required|integer|min:1',
      ]);

      try {
         $order = Order::create([
            'user_id' => $request->user_id,
            'status' => 'pending', // Default status
         ]);

         foreach ($request->cart_items as $item) {
            OrderItem::create([
               'order_id' => $order->id,
               'service_id' => $item['service_id'],
               'quantity' => $item['quantity'],
            ]);
         }

         return response()->json([
            'status' => 200,
            'message' => 'Order placed successfully.',
            'order_id' => $order->id,
         ], 201);
      } catch (\Exception $e) {
         return response()->json([
            'message' => 'Failed to place the order.',
            'error' => $e->getMessage(),
         ], 500);
      }
   }
}