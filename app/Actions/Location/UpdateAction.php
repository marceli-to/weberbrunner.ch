<?php

namespace App\Actions\Location;

use App\Models\Location;
use Illuminate\Support\Str;

class UpdateAction
{
	public function execute(Location $location, array $data): Location
	{
		$data['slug'] = Str::slug($data['title']);

		$location->update($data);

		return $location;
	}
}
