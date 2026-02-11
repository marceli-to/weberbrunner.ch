<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
	protected $model = Post::class;

	public function definition(): array
	{
		$title = fake()->unique()->sentence(3);

		return [
			'title' => $title,
			'slug' => \Illuminate\Support\Str::slug($title),
			'content' => fake()->paragraphs(3, true),
			'publish' => fake()->boolean(),
			'sort_order' => fake()->numberBetween(0, 100),
		];
	}
}
