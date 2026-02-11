<?php

namespace App\Actions\Location;

use App\Models\Location;

class DeleteAction
{
	public function execute(Location $location): void
	{
		if ($location->projects()->exists() || $location->teamMembers()->exists() || $location->jobs()->exists()) {
			throw new \Exception('Cannot delete location with related records.');
		}

		$location->delete();
	}
}
