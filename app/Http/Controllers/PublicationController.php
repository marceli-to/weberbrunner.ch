<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\View\View;

class PublicationController extends Controller
{
	public function index(): View
	{
		return view('pages.about.publications.index');
	}

	public function show(string $slug): View
	{
		$publication = Publication::published()
			->where('slug', $slug)
			->with(['attributes', 'teaser', 'download', 'blocks.media', 'blocks.links.linkedProject'])
			->firstOrFail();

		$slides = $publication->blocks->firstWhere('type', 'fixed-slider')?->media ?? collect();

		$publicationInfo = $publication->attributes->map(fn ($attr) => [
			'label' => $attr->key,
			'value' => $attr->value,
		])->toArray();

		return view('pages.about.publications.show', [
			'publication' => $publication,
			'slides' => $slides,
			'publicationInfo' => $publicationInfo,
		]);
	}
}
