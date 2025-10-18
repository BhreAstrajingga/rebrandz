<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PostStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, SoftDeletes;
    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'published_at',
        'cover_image_path',
        'video_url',
        'author_id',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'status' => PostStatus::class,
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::Published)
            ->where('published_at', '<=', now());
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Post $post): void {
            if (empty($post->slug)) {
                $source = ! empty($post->title) ? $post->title : strtolower(Str::random(8));
                $post->slug = static::generateUniqueSlug($source);
            }

            if (empty($post->excerpt) && ! empty($post->content)) {
                $post->excerpt = static::makeExcerptFromContent($post->content);
            }

            $statusValue = $post->status instanceof PostStatus ? $post->status->value : (string) $post->status;
            if ($statusValue === PostStatus::Published->value && empty($post->published_at)) {
                $post->published_at = now();
            }
        });

        static::updating(function (Post $post): void {
            $originalStatus = $post->getOriginal('status');
            if ($originalStatus === PostStatus::Published->value && $post->isDirty('slug')) {
                $post->slug = $post->getOriginal('slug');
            }

            if (empty($post->excerpt) && $post->isDirty('content')) {
                $post->excerpt = static::makeExcerptFromContent((string) $post->content);
            }

            // Ensure slug exists with fallback when missing.
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title ?: (string) $post->getKey());
            }

            // Normalize cover_image_path to a relative public storage path if a full URL was provided
            if ($post->isDirty('cover_image_path') && is_string($post->cover_image_path)) {
                $post->cover_image_path = static::normalizeCoverPath($post->cover_image_path);
            }

            $statusValue = $post->status instanceof PostStatus ? $post->status->value : (string) $post->status;
            if ($statusValue === PostStatus::Published->value && empty($post->published_at)) {
                $post->published_at = now();
            }
        });

        $invalidate = function (Post $post): void {
            $current = (int) Cache::get('blog:index:ver', 1);
            Cache::forever('blog:index:ver', $current + 1);
            Cache::forget('blog:show:' . $post->slug);
            $originalSlug = $post->getOriginal('slug');
            if ($originalSlug && $originalSlug !== $post->slug) {
                Cache::forget('blog:show:' . $originalSlug);
            }
        };

        static::saved($invalidate);
        static::deleted($invalidate);
        static::restored($invalidate);
        static::forceDeleted($invalidate);
    }

    protected static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->withTrashed()->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
    }

    protected static function makeExcerptFromContent(string $content): string
    {
        $text = strip_tags($content);
        $text = preg_replace('/\s+/', ' ', $text) ?? $text;
        return str( trim($text) )->limit(180);
    }

    protected static function normalizeCoverPath(string $path): string
    {
        $trimmed = trim($path);
        if ($trimmed === '') {
            return $trimmed;
        }

        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            $parsed = parse_url($trimmed);
            $urlPath = $parsed['path'] ?? '';
            if ($urlPath !== '' && str_starts_with($urlPath, '/storage/')) {
                return ltrim(substr($urlPath, strlen('/storage/')), '/');
            }
        }

        if (str_starts_with($trimmed, '/storage/')) {
            return ltrim(substr($trimmed, strlen('/storage/')), '/');
        }

        return ltrim($trimmed, '/');
    }

    public function setCoverImagePathAttribute(?string $value): void
    {
        $this->attributes['cover_image_path'] = $value === null
            ? null
            : static::normalizeCoverPath($value);
    }
}
