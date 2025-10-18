<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Backfill missing slugs for existing posts.
        $posts = DB::table('posts')->select('id', 'title', 'slug')->get();

        foreach ($posts as $row) {
            if (! empty($row->slug)) {
                continue;
            }

            $baseSource = $row->title ?: ('post-' . $row->id);
            $base = Str::slug((string) $baseSource);
            if ($base === '') {
                $base = 'post-' . $row->id;
            }

            $slug = $base;
            $i = 1;
            while (DB::table('posts')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = $base . '-' . $i;
                $i++;
            }

            DB::table('posts')->where('id', $row->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // Keep generated slugs; no rollback.
    }
};

