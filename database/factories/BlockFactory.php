<?php

namespace Database\Factories;

use App\Models\Block;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Block>
 */
class BlockFactory extends Factory
{
	protected $model = Block::class;

	public function definition(): array
	{
		return [
			'blockable_type' => Project::class,
			'blockable_id' => Project::factory(),
			'type' => fake()->randomElement(['text', 'slider', 'image', 'links']),
			'title' => fake()->sentence(3),
			'content' => fake()->paragraph(),
		];
	}
}
