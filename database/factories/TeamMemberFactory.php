<?php

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamMember>
 */
class TeamMemberFactory extends Factory
{
	protected $model = TeamMember::class;

	public function definition(): array
	{
		$firstname = fake()->firstName();
		$name = fake()->lastName();

		return [
			'firstname' => $firstname,
			'name' => $name,
			'email' => fake()->unique()->safeEmail(),
			'title' => fake()->jobTitle(),
			'since' => fake()->numberBetween(2000, 2026),
			'slug' => \Illuminate\Support\Str::slug($firstname . ' ' . $name),
			'publish' => true,
		];
	}
}
