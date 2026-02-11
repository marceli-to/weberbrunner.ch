<?php

namespace Database\Factories;

use App\Models\NetworkEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NetworkEntry>
 */
class NetworkEntryFactory extends Factory
{
	protected $model = NetworkEntry::class;

	public function definition(): array
	{
		return [
			'title' => fake()->company(),
			'description' => fake()->optional()->sentence(),
			'category' => fake()->optional()->word(),
			'link' => fake()->optional()->url(),
			'publish' => true,
		];
	}
}
