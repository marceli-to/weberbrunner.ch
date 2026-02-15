<?php

namespace App\Actions\TeamMember;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Collection;

class ListAction
{
	public function execute(): Collection
	{
		return TeamMember::with(['bios', 'media', 'location'])
			->orderBy('name')
			->get();
	}
}
