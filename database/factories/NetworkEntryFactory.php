<?php

namespace Database\Factories;

use App\Models\NetworkEntry;
use App\Models\Section;
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
			'text' => '<p>' . fake()->sentence(6) . '</p>',
			'section_id' => Section::factory()->state(['type' => 'network']),
			'publish' => true,
		];
	}
}
