<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
	protected $model = Media::class;

	public function definition(): array
	{
		return [
			'uuid' => Str::uuid(),
			'file' => 'uploads/' . fake()->uuid() . '.jpg',
			'original_name' => fake()->word() . '.jpg',
			'mime_type' => 'image/jpeg',
			'size' => fake()->numberBetween(1000, 5000000),
			'width' => fake()->numberBetween(100, 4000),
			'height' => fake()->numberBetween(100, 4000),
			'sort_order' => 0,
		];
	}
}
