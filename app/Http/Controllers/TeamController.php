<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;

class TeamController extends Controller
{
	public function show(string $slug)
	{
		$member = TeamMember::where('slug', $slug)
			->published()
			->with(['image', 'bios'])
			->firstOrFail();

		return view('pages.about.team-show', compact('member'));
	}
}
