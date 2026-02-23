<?php

namespace Database\Factories;

use App\Models\Section;
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
			'section_id' => Section::factory()->state(['type' => 'talk']),
			'publish' => true,
		];
	}
}
