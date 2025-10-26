<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => \App\Models\Tenant::factory(),
            'name' => $this->faker->company(),
            'code' => strtoupper($this->faker->bothify('BR-###')),
            'is_default' => false,
            'active' => true,
            'slug' => \Illuminate\Support\Str::slug($this->faker->slug()),
        ];
    }
}
