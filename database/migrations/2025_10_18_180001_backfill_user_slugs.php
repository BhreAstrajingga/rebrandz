<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Backfill missing user slugs so route-model binding works for Customer.
        $users = DB::table('users')->select('id', 'name', 'slug')->get();

        foreach ($users as $user) {
            if (! empty($user->slug)) {
                continue;
            }

            $base = Str::slug((string) ($user->name ?? 'user-' . $user->id));
            if ($base === '') {
                $base = 'user-' . $user->id;
            }

            $slug = $base;
            $i = 1;
            while (DB::table('users')->where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
                $slug = $base . '-' . $i;
                $i++;
            }

            DB::table('users')->where('id', $user->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // Keep generated slugs; no rollback.
    }
};

