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
			'text' => '<p>' . fake()->sentence(6) . '</p>',
			'link' => fake()->optional()->url(),
			'publish' => true,
		];
	}
}
