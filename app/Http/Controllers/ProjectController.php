<?php

namespace App\Http\Controllers;

use App\Actions\Project\FindBySlugAction;

class ProjectController extends Controller
{
	public function show(string $slug)
	{
		$project = (new FindBySlugAction)->execute($slug);
		return view('pages.works.show', compact('project'));
	}
}
