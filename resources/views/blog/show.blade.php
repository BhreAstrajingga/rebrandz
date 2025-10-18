<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->meta_title ?? $post->title }}</title>
    <meta name="description" content="{{ $post->meta_description ?? $post->excerpt }}">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <div class="main-content">
            <article class="about active" data-page="post">
                <header>
                    <h2 class="h2 article-title">{{ $post->title }}</h2>
                </header>
                <section class="about-text">
                    <p>{{ $post->published_at?->format('M d, Y') }}</p>
                    @if (! empty($youtubeEmbedUrl))
                        <div style="margin: 16px 0; display: flex; justify-content: center;">
                            <iframe src="{{ $youtubeEmbedUrl }}" width="100%" height="315" style="max-width: 100%; display: block;" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    @elseif ($post->cover_image_path)
                        <figure style="margin: 16px 0;">
                            <img src="{{ str_starts_with($post->cover_image_path, 'http') ? $post->cover_image_path : asset('storage/' . ltrim($post->cover_image_path, '/')) }}" alt="{{ $post->title }}" style="max-width: 100%; height: auto;">
                        </figure>
                    @endif
                    <div>{!! nl2br(e($post->content)) !!}</div>
                </section>
            </article>
        </div>
    </main>
</body>
</html>
