<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectBlock>
 */
class ProjectBlockFactory extends Factory
{
	protected $model = ProjectBlock::class;

	public function definition(): array
	{
		return [
			'project_id' => Project::factory(),
			'type' => fake()->randomElement(['text', 'slider', 'image', 'links']),
			'title' => fake()->sentence(3),
			'content' => fake()->paragraph(),
		];
	}
}
