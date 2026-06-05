<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Publication;
use App\Models\TeamMember;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
	public function __invoke(): Response
	{
		$staticPaths = [
			'/',
			'/arbeiten',
			'/buero',
			'/buero/arbeitsweisen',
			'/buero/team',
			'/buero/jobs',
			'/buero/kontakt',
			'/buero/netzwerk',
			'/buero/vortraege',
			'/buero/jury',
			'/buero/auszeichnungen',
			'/buero/publikationen',
			'/impressum',
			'/datenschutz',
		];

		$urls = [];

		foreach ($staticPaths as $path) {
			$urls[] = ['loc' => url($path), 'lastmod' => null];
		}

		foreach (Project::published()->get(['slug', 'updated_at']) as $project) {
			$urls[] = ['loc' => url('/arbeiten/' . $project->slug), 'lastmod' => $project->updated_at];
		}

		foreach (Publication::published()->get(['slug', 'updated_at']) as $publication) {
			$urls[] = ['loc' => url('/buero/publikationen/' . $publication->slug), 'lastmod' => $publication->updated_at];
		}

		foreach (TeamMember::published()->get(['slug', 'updated_at']) as $member) {
			$urls[] = ['loc' => url('/buero/team/' . $member->slug), 'lastmod' => $member->updated_at];
		}

		return response()
			->view('sitemap', compact('urls'))
			->header('Content-Type', 'application/xml');
	}
}
