<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'service_id' => 'required|integer|exists:services,id',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (! $customer) {
            return response()->json([
                'message' => 'Customer not found',
                'data' => null,
            ], 404);
        }

        $subscription = UserSubscription::where('customer_id', $customer->id)
            ->where('service_id', $request->service_id)
            ->orderByDesc('id')
            ->first();

        if (! $subscription) {
            return response()->json([
                'message' => 'No subscription found for this service',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'Subscription found',
            'data' => [
                'id' => $subscription->id,
                'service_id' => $subscription->service_id,
                'service_plan_id' => $subscription->service_plan_id,
                'status' => $subscription->status,
                'start_date' => $subscription->start_date,
                'end_date' => $subscription->end_date,
                'trial_ends_at' => $subscription->trial_ends_at,
                'cancelled_at' => $subscription->cancelled_at,
            ],
        ]);
    }
}
