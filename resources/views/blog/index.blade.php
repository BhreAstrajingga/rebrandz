<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <div class="main-content">
            <article class="portfolio active" data-page="blog">
                <header>
                    <h2 class="h2 article-title">Blog</h2>
                </header>
                <section class="projects">
                    <ul class="project-list">
                        @forelse ($posts as $post)
                            <li class="project-item active">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <figure class="project-img">
                                        <img src="{{ $post->cover_image_path ? (str_starts_with($post->cover_image_path, 'http') ? $post->cover_image_path : asset('storage/' . ltrim($post->cover_image_path, '/'))) : '/assets/images/icon-dev.svg' }}" alt="{{ $post->title }}">
                                    </figure>
                                    <h3 class="project-title">{{ $post->title }}</h3>
                                    <p class="project-category">{{ $post->published_at?->format('M d, Y') }}</p>
                                    <p class="project-category">{{ $post->excerpt }}</p>
                                </a>
                            </li>
                        @empty
                            <li>Tidak ada artikel.</li>
                        @endforelse
                    </ul>
                    <div style="margin-top: 16px;">
                        {{ $posts->links('blog.pagination') }}
                    </div>
                </section>
            </article>
        </div>
    </main>
</body>
</html>
