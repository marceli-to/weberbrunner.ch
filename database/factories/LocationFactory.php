<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
	protected $model = Location::class;

	public function definition(): array
	{
		$title = fake()->unique()->city();

		return [
			'title' => $title,
			'slug' => \Illuminate\Support\Str::slug($title),
		];
	}
}
