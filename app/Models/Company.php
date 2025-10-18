<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'mysql_secondary';
    protected $table = 'companies';

    public function profile()
    {
        return $this->hasOne(CompanyProfile::class, 'company_id');
    }
}
