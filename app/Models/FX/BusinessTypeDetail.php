<?php

namespace App\Models\FX;

use Illuminate\Database\Eloquent\Model;

class BusinessTypeDetail extends Model
{
    protected $table = 'business_type_details';

    protected $fillable = [
        'description',
        'quantity',
        'unit',
        'price',
        'business_type',
    ];

    public function businessType()
    {
        return $this->belongsTo(BusinessType::class, 'business_type');
    }
}
