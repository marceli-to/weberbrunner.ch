<?php

namespace Database\Factories;

use App\Models\Publication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PublicationFactory extends Factory
{
	protected $model = Publication::class;

	public function definition(): array
	{
		$title = fake()->sentence(3);

		return [
			'title' => $title,
			'slug' => Str::slug($title),
			'subtitle' => fake()->optional()->sentence(4),
			'publish' => fake()->boolean(),
		];
	}
}
