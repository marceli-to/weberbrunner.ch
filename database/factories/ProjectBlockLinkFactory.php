<?php

namespace Database\Factories;

use App\Models\ProjectBlock;
use App\Models\ProjectBlockLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectBlockLink>
 */
class ProjectBlockLinkFactory extends Factory
{
	protected $model = ProjectBlockLink::class;

	public function definition(): array
	{
		return [
			'project_block_id' => ProjectBlock::factory(),
			'title' => fake()->sentence(3),
			'url' => fake()->url(),
			'link_type' => 'external',
			'publish' => true,
		];
	}
}
