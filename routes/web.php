<?php

use App\Http\Controllers\Admin\CompanyProfileController;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $posts = \App\Models\Post::query()
        ->select(['id', 'title', 'slug', 'excerpt', 'published_at', 'cover_image_path'])
        ->published()
        ->latest('published_at')
        ->limit(4)
        ->get();

    $hasMoreBlogPosts = \App\Models\Post::query()
        ->published()
        ->count() > 4;

    return view('welcome', compact('posts', 'hasMoreBlogPosts'));
});


Route::get('/profile-payload', [CompanyProfileController::class, 'profilePayload'])
    ->name('profile.payload');

Route::get('/profile-view', [CompanyProfileController::class, 'view'])
    ->name('profile.view');

Route::get('/profile-attachment', [CompanyProfileController::class, 'downloadAttachment'])
    ->name('profile.attachment.download');

Route::get('/profile-consume', [CompanyProfileController::class, 'consume'])
    ->name('profile.consume');

// Blog public routes with throttling
Route::middleware(['throttle:blog-public'])->group(function () {
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
});

// Browser sessions management moved to Filament Page
