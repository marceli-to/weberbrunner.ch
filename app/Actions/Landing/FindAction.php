<?php

namespace App\Actions\Landing;

use App\Models\Landing;

class FindAction
{
	public function execute(): Landing
	{
		return Landing::firstOrCreate([], [
			'text' => null,
			'publish' => false,
		]);
	}
}
