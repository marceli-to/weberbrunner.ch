<?php

namespace App\Actions\Project;

use App\Models\Project;

class FindBySlugAction
{
	public function execute(string $slug): Project
	{
		return Project::where('slug', $slug)
			->with(['attributes', 'media', 'categories', 'statuses'])
			->firstOrFail();
	}
}
