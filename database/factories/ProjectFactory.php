<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
	protected $model = Project::class;

	public function definition(): array
	{
		$title = fake()->unique()->sentence(3);
		$city = fake()->city();

		return [
			'title' => $title,
			'number' => fake()->unique()->numberBetween(100, 9999),
			'slug' => \Illuminate\Support\Str::slug($title . ' ' . $city),
			'short_description' => fake()->sentence(),
			'description' => fake()->paragraph(),
			'city' => $city,
			'publish' => fake()->boolean(),
		];
	}
}
