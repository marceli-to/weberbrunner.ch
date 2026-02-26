<?php

namespace Database\Factories;

use App\Models\LandingItem;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LandingItem>
 */
class LandingItemFactory extends Factory
{
	protected $model = LandingItem::class;

	public function definition(): array
	{
		return [
			'project_id' => Project::factory(),
			'column' => fake()->numberBetween(1, 3),
		];
	}
}
