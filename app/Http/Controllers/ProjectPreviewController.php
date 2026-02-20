<?php

namespace App\Http\Controllers;

use App\Actions\Project\FindBySlugAction;

class ProjectPreviewController extends Controller
{
	public function show(string $slug)
	{
		$project = (new FindBySlugAction)->execute($slug);

		return view('pages.works.show', [
			'project' => $project,
			'isPreview' => true,
		]);
	}
}
