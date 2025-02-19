<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StripeWebhookController extends Controller
{
   public function handleWebhook(Request $request)
   {
      $payload = $request->all();
      $eventType = $payload['type'] ?? null;

      if ($eventType === 'checkout.session.completed') {
         $session = $payload['data']['object'];
         $userId = $session['metadata']['userId'] ?? null;
         $phoneNumber = $session['customer_details']['phone'] ?? null;

         if (!$userId) {
            return response()->json(['message' => 'User ID missing in metadata'], 400);
         }

         $cartItems = Cart::where('user_id', $userId)->get();

         if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
         }

         $order = Order::create([
            'user_id' => $userId,
            'status' => 'paid',
            'phone_number' => $phoneNumber,
         ]);

         foreach ($cartItems as $item) {
            OrderItem::create([
               'order_id' => $order->id,
               'service_id' => $item->service_id,
               'quantity' => $item->quantity ?? 1,
            ]);
         }
         Cart::where('user_id', $userId)->delete();
         return response()->json(['message' => 'Order created successfully'], 200);
      }

      return response()->json(['message' => 'Unhandled event'], 400);
   }

}