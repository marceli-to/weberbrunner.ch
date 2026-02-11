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

		return [
			'title' => $title,
			'slug' => \Illuminate\Support\Str::slug($title),
			'description' => fake()->paragraph(),
			'publish' => fake()->boolean(),
		];
	}
}
