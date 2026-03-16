<?php

namespace App\Http\Controllers;

use App\Actions\Project\FindBySlugAction;
use App\Actions\Project\PrepareViewDataAction;

class ProjectPreviewController extends Controller
{
	public function show(string $slug)
	{
		$project = (new FindBySlugAction)->execute($slug);

		return view('pages.works.show', (new PrepareViewDataAction)->execute($project, true));
	}
}
