<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
	protected $model = Job::class;

	public function definition(): array
	{
		return [
			'title' => fake()->jobTitle(),
			'description' => fake()->paragraph(),
			'contact_email' => fake()->safeEmail(),
			'publish' => fake()->boolean(),
		];
	}
}
