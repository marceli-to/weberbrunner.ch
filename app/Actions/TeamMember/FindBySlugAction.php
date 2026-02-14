<?php

namespace App\Actions\TeamMember;

use App\Models\TeamMember;

class FindBySlugAction
{
	public function execute(string $slug): TeamMember
	{
		return TeamMember::where('slug', $slug)
			->published()
			->with(['image', 'bios'])
			->firstOrFail();
	}
}
