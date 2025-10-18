<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyProfile extends Model
{
    protected $connection = 'mysql_secondary'; // remark: tetap koneksi ke db yang sama
    protected $table = 'company_profiles';
    protected $fillable = ['temp_token'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Consume one-time temp token for the given alias.
     */
    public static function consumeTempTokenByAlias(string $alias, string $token): ?self
    {
        return DB::transaction(function () use ($alias, $token) {
            $profile = self::query()
                ->where('company_alias', $alias)
                ->where('temp_token', $token)
                ->lockForUpdate()
                ->first();

            if (! $profile) {
                return null;
            }

            $profile->temp_token = bin2hex(random_bytes(16));
            $profile->save();

            return $profile;
        });
    }
}
