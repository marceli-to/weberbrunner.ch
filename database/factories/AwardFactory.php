<?php

namespace Database\Factories;

use App\Models\Award;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Award>
 */
class AwardFactory extends Factory
{
	protected $model = Award::class;

	public function definition(): array
	{
		return [
			'text' => '<p>' . fake()->sentence(6) . '</p>',
			'publish' => true,
		];
	}
}
