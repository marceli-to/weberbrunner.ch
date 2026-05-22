<?php

namespace App\Actions\TeamMember;

use App\Models\TeamMember;
use Illuminate\Support\Str;

class StoreAction
{
	public function execute(array $data): TeamMember
	{
		$data['slug'] = Str::slug($data['firstname'] . ' ' . $data['name']);

		return TeamMember::create($data);
	}
}
