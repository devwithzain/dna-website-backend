<?php

namespace App\Http\Controllers\Api;

use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Service;
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
         return response()->json(['message' => 'No services found for the items in the cart'], 404);
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
            'success_url' => env('FRONTEND_WEBSITE_URL') . '/thankyou?session_id={CHECKOUT_SESSION_ID}&success=1',
            'cancel_url' => env('FRONTEND_WEBSITE_URL') . '/cart?canceled=1',
         ]);

         return response()->json(['url' => $session->url]);
      } catch (\Exception $e) {
         return response()->json(['message' => $e->getMessage()], 500);
      }
   }

}