<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'service_id' => 'required|integer',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (! $customer) {
            return response()->json([
                'message' => 'Customer not found',
            ], 404);
        }

        $subscription = UserSubscription::with(['service', 'plan'])
            ->where('customer_id', $customer->id)
            ->where('service_id', $request->service_id)
            ->latest('start_date')
            ->first();

        if (! $subscription) {
            return response()->json([
                'message' => 'No active subscription found',
            ], 404);
        }

        // tentukan status aktual
        $now = now();
        $status = 'EXPIRED';

        if ($subscription->cancelled_at) {
            $status = 'CANCELLED';
        } elseif ($subscription->start_date && $now->lt($subscription->start_date)) {
            $status = 'PENDING';
        } elseif ($subscription->trial_ends_at && $now->lt($subscription->trial_ends_at)) {
            $status = 'TRIAL';
        } elseif ($subscription->end_date && $now->lt($subscription->end_date)) {
            $status = 'ACTIVE';
        }

        $plan = $subscription->plan;
        $durationText = null;

        if ($plan?->duration && $plan?->interval) {
            $intervalLabel = match ($plan->interval) {
                'daily'   => 'day',
                'monthly' => 'month',
                'yearly'  => 'year',
                default   => $plan->interval,
            };

            $durationText = $plan->duration . ' ' . \Str::plural($intervalLabel, $plan->duration);
        }

        return response()->json([
            'message' => 'Subscription found',
            'status'  => $status, // otomatisasi berdasarkan tanggal
            'data' => [
                'service_name'   => $subscription->service?->name,
                'plan_name'      => $plan?->name,
                'status'         => $status,
                'start_date'     => optional($subscription->start_date)->format('d F Y'),
                'end_date'       => optional($subscription->end_date)->format('d F Y'),
                'trial_ends_at'  => optional($subscription->trial_ends_at)->format('d F Y'),
                'cancelled_at'   => optional($subscription->cancelled_at)->format('d F Y'),
                'duration'       => $durationText,
                'features'       => $plan?->features ?? [],
            ],
        ]);
    }


    public function license_check(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'email' => 'nullable|email',
            'build_id' => 'nullable|string',
            'device_id' => 'nullable|string',
        ]);

        $subscription = UserSubscription::with(['service', 'plan', 'customer'])
            ->where('subscription_code', $request->code)
            ->first();

        if (! $subscription) {
            return response()->json([
                'message' => 'License not found',
                'status'  => 'INVALID',
            ], 404);
        }

        $now = now();
        $status = 'EXPIRED';

        // urutan logika: Cancelled → Pending → Trial → Active → Expired
        if ($subscription->cancelled_at) {
            $status = 'CANCELLED';
        } elseif ($subscription->start_date && $now->lt($subscription->start_date)) {
            $status = 'PENDING'; // masa aktif belum dimulai
        } elseif ($subscription->trial_ends_at && $now->lt($subscription->trial_ends_at)) {
            $status = 'TRIAL';
        } elseif ($subscription->end_date && $now->lt($subscription->end_date)) {
            $status = 'ACTIVE';
        }

        $plan = $subscription->plan;
        $durationText = null;

        if ($plan?->duration && $plan?->interval) {
            $intervalLabel = match ($plan->interval) {
                'daily'   => 'day',
                'monthly' => 'month',
                'yearly'  => 'year',
                default   => $plan->interval,
            };
            $durationText = $plan->duration . ' ' . \Str::plural($intervalLabel, $plan->duration);
        }

        // log aktivitas validasi (tanpa trigger observer)
        $subscription->updateQuietly(['last_validated_at' => now()]);

        return response()->json([
            'message' => 'License validation successful',
            'status'  => $status,
            'expires_at' => optional($subscription->end_date)?->toIso8601String(),
            'data' => [
                'customer_email' => $subscription->customer?->email,
                'service_name'   => $subscription->service?->name,
                'plan_name'      => $plan?->name,
                'status'         => $status,
                'start_date'     => optional($subscription->start_date)->format('d F Y'),
                'end_date'       => optional($subscription->end_date)->format('d F Y'),
                'trial_ends_at'  => optional($subscription->trial_ends_at)->format('d F Y'),
                'cancelled_at'   => optional($subscription->cancelled_at)->format('d F Y'),
                'duration'       => $durationText,
                'features'       => $plan?->features ?? [],
            ],
        ]);
    }
}
