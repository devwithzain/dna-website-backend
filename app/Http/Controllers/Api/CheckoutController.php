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

class CheckoutController extends Controller
{
   public function createSession(Request $request)
   {
      $userId = $request->input('userId');

      $cartItems = Cart::where('user_id', $userId)->get();
      if ($cartItems->isEmpty()) {
         return response()->json(['message' => 'No items in the cart'], 404);
      }

      $serviceIds = $cartItems->pluck('service_id')->toArray();
      $services = Service::whereIn('id', $serviceIds)->get();
      if ($services->isEmpty()) {
         return response()->json(['message' => 'No services found'], 404);
      }

      $lineItems = [];
      foreach ($services as $service) {
         $lineItems[] = [
            'quantity' => 1,
            'price_data' => [
               'currency' => 'USD',
               'product_data' => [
                  'name' => $service->title,
                  'description' => $service->description,
               ],
               'unit_amount' => round($service->price * 100),
            ],
         ];
      }

      try {
         Stripe::setApiKey(env('STRIPE_SECRET'));
         $session = CheckoutSession::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'billing_address_collection' => 'required',
            'phone_number_collection' => ['enabled' => true],
            'metadata' => [
               'userId' => $userId,
            ],
            'success_url' => env('FRONTEND_WEBSITE_URL') . '/?session_id={CHECKOUT_SESSION_ID}&success=1',
            'cancel_url' => env('FRONTEND_WEBSITE_URL') . '/cart?canceled=1',
         ]);

         // Save order after successful session creation
         $order = Order::create([
            'user_id' => $userId,
            'status' => 'pending',
            'phone_number' => $session->customer_details->phone ?? null,
         ]);

         foreach ($cartItems as $item) {
            OrderItem::create([
               'order_id' => $order->id,
               'service_id' => $item->service_id,
               'quantity' => $item->quantity ?? 1,
            ]);
         }

         // Clear cart after order creation
         Cart::where('user_id', $userId)->delete();

         return response()->json(['url' => $session->url]);
      } catch (\Exception $e) {
         return response()->json(['message' => $e->getMessage()], 500);
      }
   }
}