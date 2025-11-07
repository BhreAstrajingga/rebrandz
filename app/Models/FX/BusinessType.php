<?php

namespace App\Models\FX;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessType extends Model
{
    // use SoftDeletes;

    protected $table = 'business_types';

    protected $fillable = [
        'name',
    ];

    public function details()
    {
        return $this->hasMany(BusinessTypeDetail::class, 'business_type');
    }

    protected static function booted(): void
    {
        static::deleting(function (BusinessType $model): void {
            $model->details()->delete();
        });
    }
}
