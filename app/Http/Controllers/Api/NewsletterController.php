<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\NewsletterSubscriber;
use App\Http\Controllers\Controller;
use App\Mail\NewsletterSubscribedMail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email'
        ]);

        try {
            // Save to the database
            $subscriber = NewsletterSubscriber::create($data);

            // Send email notification to the admin
            Mail::to(config('mail.from.address'))->send(new NewsletterSubscribedMail($subscriber));

            return response()->json(['success' => 'Subscribed successfully!'], 200);
        } catch (\Exception $e) {
            Log::error('Newsletter subscription failed: ' . $e->getMessage());
            return response()->json(['error' => 'Subscription failed. Please try again.'], 500);
        }
    }
}