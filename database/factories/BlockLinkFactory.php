<?php

namespace Database\Factories;

use App\Models\Block;
use App\Models\BlockLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlockLink>
 */
class BlockLinkFactory extends Factory
{
	protected $model = BlockLink::class;

	public function definition(): array
	{
		return [
			'block_id' => Block::factory(),
			'title' => fake()->sentence(3),
			'url' => fake()->url(),
			'link_type' => 'external',
			'publish' => true,
		];
	}
}
