<?php

namespace App\Http\Controllers;

use App\Actions\Project\PrepareViewDataAction;
use App\Models\Project;

class ProjectController extends Controller
{
	public function show(string $slug)
	{
		$project = Project::published()
			->where('slug', $slug)
			->with(['masterdata', 'teaser', 'categories', 'statuses', 'blocks.media', 'blocks.links.linkedProject'])
			->firstOrFail();

		return view('pages.works.show', (new PrepareViewDataAction)->execute($project, false));
	}
}
