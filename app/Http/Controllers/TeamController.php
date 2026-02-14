<?php

namespace App\Http\Controllers;

use App\Actions\TeamMember\FindBySlugAction;

class TeamController extends Controller
{
	public function show(string $slug)
	{
		$member = (new FindBySlugAction)->execute($slug);
		return view('pages.about.team-show', compact('member'));
	}
}
