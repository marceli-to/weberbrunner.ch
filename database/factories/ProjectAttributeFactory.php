<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectAttribute>
 */
class ProjectAttributeFactory extends Factory
{
	protected $model = ProjectAttribute::class;

	public function definition(): array
	{
		return [
			'project_id' => Project::factory(),
			'label' => fake()->word(),
			'value' => fake()->sentence(),
		];
	}
}
