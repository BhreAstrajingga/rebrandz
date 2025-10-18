<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    /*
        Flow Subscription:

        1. Pembuatan Subscription:
        - Admin/User memilih ServicePlan.
        - service_id otomatis diisi dari plan.
        - start_date diisi sekarang (untuk trial atau langsung paid).
        - end_date dihitung dari start_date + plan interval * duration.
        - Jika plan trial/free → status = 'trial' dan trial_ends_at = start_date + durasi trial.
        - Jika plan berbayar → status = 'pending' sampai pembayaran diterima.

        2. Saat pembayaran diterima (untuk paid plan):
        - status → 'active'
        - start_date di-set (jika belum diisi)
        - end_date dihitung berdasarkan plan interval * duration.

        3. Masa aktif berjalan:
        - Sistem memeriksa end_date untuk menentukan status 'expired'.

        4. Trial plan selesai:
        - status → 'active' jika user upgrade ke plan berbayar, atau 'expired' jika tidak.

        5. Pembatalan (cancel):
        - Jika user/admin membatalkan subscription sebelum end_date:
            - cancelled_at diisi tanggal pembatalan.
            - status → 'cancelled'.
            - Subscription tidak diperpanjang lagi, end_date tetap (atau optional disesuaikan kebijakan).

        6. Perpanjangan otomatis/manual (renewal_type):
        - Saat end_date mendekat, sistem bisa memproses auto-renew jika renewal_type = 'auto'.
        - Jika manual → user harus melakukan pembayaran manual.

        Catatan:
        - Semua tanggal di DB disimpan sebagai datetime.
        - status harus konsisten dengan tanggal dan cancelled_at/trial_ends_at.
        - service_id tetap disimpan untuk query dan histori, meskipun redundant secara relasi.
        */

    protected $fillable = [
        'customer_id',          // ID user yang memiliki subscription
        'service_id',       // ID service, otomatis diisi berdasarkan service_plan_id
        'service_plan_id',  // ID plan yang dipilih user
        'subscription_code',
        'start_date',       // Tanggal mulai subscription (aktif atau trial)
        'end_date',         // Tanggal berakhir subscription
        'status',           // Status subscription: active, expired, cancelled, pending, trial
        'renewal_type',     // Cara perpanjangan: manual / auto (opsional, sementara bisa diabaikan)
        'trial_ends_at',    // Tanggal akhir trial (hanya untuk plan free/trial)
        'cancelled_at',     // Tanggal user/admin membatalkan subscription sebelum berakhir
    ];

    protected $casts = [
        'start_date'     => 'datetime',
        'end_date'       => 'datetime',
        'trial_ends_at'  => 'datetime',
        'cancelled_at'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            // Pastikan ada kode
            $model->subscription_code = $model->subscription_code ?: (string) \Str::uuid();

            // Isi status otomatis jika kosong atau null
            if (blank($model->status)) {
                $model->status = self::determineStatus($model);
            }
        });

        static::updating(function ($model) {
            // Larang perubahan kode
            if ($model->isDirty('subscription_code')) {
                throw new \Exception('subscription_code tidak boleh diubah.');
            }

            // Jika kosong (edge-case)
            if (blank($model->subscription_code)) {
                $model->subscription_code = (string) \Str::uuid();
            }

            // Tentukan status baru berdasarkan tanggal
            $newStatus = self::determineStatus($model);

            // Jika status belum ada atau tidak sesuai, ubah
            if (blank($model->status) || $model->status !== $newStatus) {
                $model->status = $newStatus;
            }
        });
    }


    public static function determineStatus($subscription): string
    {
        $now = now();

        if ($subscription->cancelled_at) {
            return 'CANCELLED';
        }

        if ($subscription->start_date && $now->lt($subscription->start_date)) {
            return 'PENDING';
        }

        if ($subscription->trial_ends_at && $now->lt($subscription->trial_ends_at)) {
            return 'TRIAL';
        }

        if ($subscription->end_date && $now->lt($subscription->end_date)) {
            return 'ACTIVE';
        }

        return 'EXPIRED';
    }


    // Subscription milik seorang user
    public function customer()
    {
        return $this->belongsTo(Customer::class)->where('user_type', 'customer');
    }

    // Subscription terkait ke service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Subscription terkait ke plan
    public function plan()
    {
        return $this->belongsTo(ServicePlan::class, 'service_plan_id');
    }

    // Subscription bisa punya banyak pembayaran
    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }
}
