<?php

namespace Database\Factories;

use App\Models\Talk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Talk>
 */
class TalkFactory extends Factory
{
	protected $model = Talk::class;

	public function definition(): array
	{
		return [
			'title' => fake()->sentence(3),
			'event' => fake()->company(),
			'location' => fake()->city(),
			'date' => fake()->date(),
			'link' => fake()->optional()->url(),
			'publish' => true,
		];
	}
}
