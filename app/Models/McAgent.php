<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class McAgent extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'name',
		'agent_code',
		'email',
		'phone',
	];

	protected static function booted()
	{
		static::creating(function (McAgent $agent) {
			if (filled($agent->agent_code)) return;

			if (blank($agent->name)) {
				throw new \Exception('Agent need name to be recognized and identified.');
			}

			$words = explode(' ', strtoupper($agent->name));
			$prefix = count($words) >= 2
				? substr($words[0], 0, 1) . substr($words[1], 0, 1)
				: substr($words[0], 0, 2);

			$maxAttempts = 5;
			$attempt = 0;

			do {
				$suffix = strtoupper(substr(dechex(crc32($agent->name . microtime() . $attempt)), 0, 2));
				$code = $prefix . $suffix;
				$attempt++;
			} while (
				self::where('agent_code', $code)->exists() && $attempt < $maxAttempts
			);

			if (self::where('agent_code', $code)->exists()) {
				throw new \Exception('Fail to generate agent code. Please try again.');
			}

			$agent->agent_code = $code;
		});
	}
}
