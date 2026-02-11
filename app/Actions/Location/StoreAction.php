<?php

namespace App\Actions\Location;

use App\Models\Location;
use Illuminate\Support\Str;

class StoreAction
{
	public function execute(array $data): Location
	{
		$data['slug'] = Str::slug($data['title']);

		return Location::create($data);
	}
}
