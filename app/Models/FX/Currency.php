<?php

namespace App\Models\FX;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'symbol',
        'name',
		'alias',
        'country',
        'suffix',
    ];

    public function order()
    {
        // return $this->hasMany(Order::class, 'currency_id', 'id');
    }
}
