<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    protected $fillable = [
        'user_subscription_id',
        'amount',
        'payment_method',
        'provider',
        'transaction_id',
        'status',
        'paid_at',
        'note',
    ];


    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }
}
