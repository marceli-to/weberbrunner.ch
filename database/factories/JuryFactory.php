<?php

namespace Database\Factories;

use App\Models\Jury;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jury>
 */
class JuryFactory extends Factory
{
	protected $model = Jury::class;

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
