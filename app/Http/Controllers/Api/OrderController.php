<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\CheckoutDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
   public function getAllOrders()
   {
      $orders = Order::with(['items.service', 'user', 'checkoutDetail'])
         ->orderBy('created_at', 'desc')
         ->get();
      return response()->json($orders);
   }
   public function getAllOrdersForUser()
   {
      $user = Auth::user();
      if (!$user) {
         return response()->json(['message' => 'Unauthorized'], 401);
      }

      $orders = Order::where('user_id', $user->id)
         ->with(['items.service', 'user', 'checkoutDetail'])
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
         \Log::info('Starting order placement', ['user_id' => $request->user_id]);

         $order = Order::create([
            'user_id' => $request->user_id,
            'status' => 'pending',
         ]);

         \Log::info('Order created', ['order_id' => $order->id]);

         foreach ($request->cart_items as $item) {
            OrderItem::create([
               'order_id' => $order->id,
               'service_id' => $item['service_id'],
               'quantity' => $item['quantity'],
            ]);
         }

         \Log::info('Order items created', ['order_id' => $order->id, 'items_count' => count($request->cart_items)]);

         // Create CheckoutDetail
         CheckoutDetail::create([
            'order_id' => $order->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'country' => $request->country,
            'street_address' => $request->street_address,
            'town_city' => $request->town_city,
            'state' => $request->state,
            'zip' => $request->zip,
            'agreed_terms' => $request->agreed_terms,
         ]);

         \Log::info('Checkout details created', ['order_id' => $order->id]);

         return response()->json([
            'status' => 200,
            'message' => 'Order placed successfully.',
            'order_id' => $order->id,
         ], 201);
      } catch (\Exception $e) {
         \Log::error('Order placement failed', [
            'error' => $e->getMessage(),
            'user_id' => $request->user_id,
            'trace' => $e->getTraceAsString()
         ]);

         return response()->json([
            'message' => 'Failed to place the order.',
            'error' => $e->getMessage(),
         ], 500);
      }
   }
}