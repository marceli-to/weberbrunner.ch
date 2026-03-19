<?php

namespace Database\Factories;

use App\Models\Publication;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationFactory extends Factory
{
	protected $model = Publication::class;

	public function definition(): array
	{
		return [
			'title' => fake()->sentence(3),
			'subtitle' => fake()->optional()->sentence(4),
			'publish' => fake()->boolean(),
		];
	}
}
