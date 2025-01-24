<?php

namespace App\Http\Controllers\Api;

use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Service;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Checkout\Session as CheckoutSession;


class OrderController extends Controller
{
   public function createOrder(Request $request)
   {
      $sessionId = $request->input('session_id'); // Get session ID passed from frontend

      // Verify the Stripe session
      try {
         Stripe::setApiKey(env('STRIPE_SECRET'));

         $session = CheckoutSession::retrieve($sessionId);

         if ($session->payment_status != 'paid') {
            return response()->json(['message' => 'Payment not successful'], 400);
         }

         // Create Order
         $order = new Order();
         $order->user_id = $session->metadata->userId; // From the metadata in the session
         $order->total_amount = $session->amount_total / 100; // Convert cents to dollars
         $order->status = 'pending'; // Or any initial status
         $order->save();

         // Create Order Items
         $cartItems = Cart::where('user_id', $order->user_id)->get();
         foreach ($cartItems as $cartItem) {
            $service = Service::find($cartItem->service_id);

            // Create order item for each product in the cart
            OrderItem::create([
               'order_id' => $order->id,
               'service_id' => $service->id,
               'quantity' => $cartItem->quantity,
               'price' => $service->price,
            ]);
         }

         // Optionally, empty the cart after order creation
         Cart::where('user_id', $order->user_id)->delete();

         return response()->json(['message' => 'Order created successfully', 'order_id' => $order->id], 200);
      } catch (\Exception $e) {
         return response()->json(['message' => $e->getMessage()], 500);
      }
   }
}