<?php

namespace App\Http\Controllers\Api;

use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Service;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

      $totalAmount = 0;
      $lineItems = [];
      foreach ($services as $service) {
         $totalAmount += $service->price;
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
         $paymentIntent = PaymentIntent::create([
            'amount' => round($totalAmount * 100),
            'currency' => 'USD',
            'payment_method_types' => ['card'],
         ]);

         return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
         ]);

      } catch (\Exception $e) {
         return response()->json(['message' => $e->getMessage()], 500);
      }
   }

}