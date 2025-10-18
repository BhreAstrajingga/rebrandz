<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Backfill missing slugs for existing services.
        $services = DB::table('services')->select('id', 'name', 'slug')->get();

        foreach ($services as $row) {
            if (! empty($row->slug)) {
                continue;
            }

            $base = Str::slug((string) ($row->name ?? 'service-' . $row->id));
            if ($base === '') {
                $base = 'service-' . $row->id;
            }

            $slug = $base;
            $i = 1;
            while (DB::table('services')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = $base . '-' . $i;
                $i++;
            }

            DB::table('services')->where('id', $row->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // Keep generated slugs; no rollback.
    }
};

