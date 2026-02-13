<?php

namespace Database\Factories;

use App\Models\Award;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Award>
 */
class AwardFactory extends Factory
{
	protected $model = Award::class;

	public function definition(): array
	{
		return [
			'title' => fake()->sentence(3),
			'description' => fake()->optional()->sentence(),
			'link' => fake()->optional()->url(),
			'publish' => true,
		];
	}
}
