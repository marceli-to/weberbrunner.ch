<?php

namespace Database\Factories;

use App\Models\PublicationAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationAttributeFactory extends Factory
{
	protected $model = PublicationAttribute::class;

	public function definition(): array
	{
		return [
			'key' => fake()->word(),
			'value' => fake()->sentence(),
		];
	}
}
