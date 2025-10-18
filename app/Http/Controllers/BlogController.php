<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    public function index(Request $request): ViewContract
    {
        $page = max(1, (int) $request->get('page', 1));
        $version = Cache::get('blog:index:ver', 1);
        $cacheKey = 'blog:index:' . $page . ':v' . $version;

        $posts = Cache::remember($cacheKey, 300, function () {
            return Post::query()
                ->select(['id', 'title', 'slug', 'excerpt', 'published_at', 'cover_image_path'])
                ->published()
                ->latest('published_at')
                ->paginate(5);
        });

        return view('blog.index', [
            'posts' => $posts,
        ]);
    }

    public function show(string $slug): ViewContract
    {
        $cacheKey = 'blog:show:' . $slug;

        $post = Cache::remember($cacheKey, 300, function () use ($slug) {
            return Post::query()
                ->select(['id', 'title', 'slug', 'excerpt', 'content', 'published_at', 'cover_image_path', 'video_url', 'meta_title', 'meta_description'])
                ->published()
                ->where('slug', $slug)
                ->firstOrFail();
        });

        $videoId = $this->extractYoutubeId((string) $post->video_url);
        $youtubeEmbedUrl = $videoId ? ('https://www.youtube-nocookie.com/embed/' . $videoId) : null;

        return view('blog.show', [
            'post' => $post,
            'youtubeEmbedUrl' => $youtubeEmbedUrl,
        ]);
    }

    protected function extractYoutubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $url = trim($url);
        if (preg_match('~youtu\.be/([\w-]{11})~', $url, $m)) {
            return $m[1];
        }
        if (preg_match('~[?&]v=([\w-]{11})~', $url, $m)) {
            return $m[1];
        }
        if (preg_match('~/embed/([\w-]{11})~', $url, $m)) {
            return $m[1];
        }

        return null;
    }
}
