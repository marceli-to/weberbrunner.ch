<?php

namespace Database\Factories;

use App\Models\TeamMember;
use App\Models\TeamMemberBio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamMemberBio>
 */
class TeamMemberBioFactory extends Factory
{
	protected $model = TeamMemberBio::class;

	public function definition(): array
	{
		return [
			'team_member_id' => TeamMember::factory(),
			'period' => fake()->year() . ' - ' . fake()->year(),
			'description' => fake()->sentence(),
		];
	}
}
