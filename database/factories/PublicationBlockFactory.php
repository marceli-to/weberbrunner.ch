<?php

namespace Database\Factories;

use App\Models\PublicationBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationBlockFactory extends Factory
{
	protected $model = PublicationBlock::class;

	public function definition(): array
	{
		return [
			'type' => fake()->randomElement(['slider', 'download', 'link']),
			'title' => fake()->optional()->sentence(3),
			'content' => fake()->optional()->paragraph(),
			'url' => fake()->optional()->url(),
		];
	}
}
