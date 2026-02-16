<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectLink>
 */
class ProjectLinkFactory extends Factory
{
	protected $model = ProjectLink::class;

	public function definition(): array
	{
		return [
			'project_id' => Project::factory(),
			'url' => fake()->url(),
		];
	}
}
