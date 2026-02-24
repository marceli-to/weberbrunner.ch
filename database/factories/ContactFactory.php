<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
	protected $model = Contact::class;

	public function definition(): array
	{
		return [
			'company_name' => fake()->company(),
			'address' => fake()->address(),
			'phone' => fake()->phoneNumber(),
			'email' => fake()->safeEmail(),
			'maps_url' => 'https://maps.google.com',
			'publish' => fake()->boolean(),
		];
	}
}
