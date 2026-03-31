<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Actions\Publication\PrepareViewDataAction;
use Illuminate\View\View;

class PublicationPreviewController extends Controller
{
	public function show(string $slug): View
	{
		$publication = Publication::where('slug', $slug)
			->with(['attributes', 'teaser', 'download', 'blocks.media', 'blocks.links.linkedProject'])
			->firstOrFail();

		return view('pages.about.publications.show', (new PrepareViewDataAction)->execute($publication, true));
	}
}
