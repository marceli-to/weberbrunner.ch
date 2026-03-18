<?php

namespace App\Actions\Location;

use App\Models\Location;
use Illuminate\Validation\ValidationException;

class DeleteAction
{
	public function execute(Location $location): void
	{
		if ($location->projects()->exists() || $location->teamMembers()->exists() || $location->jobs()->exists()) {
			throw ValidationException::withMessages([
				'location' => 'Cannot delete location with related records.',
			]);
		}

		$location->delete();
	}
}
