<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\PostStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'title' => $title,
            'slug' => str()->slug($title) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'excerpt' => $this->faker->paragraph(),
            'content' => $this->faker->paragraphs(5, true),
            'status' => PostStatus::Draft,
            'published_at' => null,
            'cover_image_path' => null,
            'author_id' => null,
            'meta_title' => null,
            'meta_description' => null,
        ];
    }

    public function published(): self
    {
        return $this->state(function (array $attributes): array {
            return [
                'status' => PostStatus::Published,
                'published_at' => now(),
            ];
        });
    }

    public function scheduled(): self
    {
        return $this->state(function (array $attributes): array {
            return [
                'status' => PostStatus::Scheduled,
                'published_at' => now()->addDays($this->faker->numberBetween(1, 14)),
            ];
        });
    }
}
