<?php

namespace App\Actions\Project;

use App\Models\Project;
use Illuminate\Support\Collection;

class ListAction
{
	public function execute(): Collection
	{
		return Project::query()
			->with(['media' => fn ($q) => $q->where('is_teaser', true)])
			->latest()
			->get()
			->map(function (Project $project) {
				$media = $project->media->first();
				return [
					'title' => $project->title,
					'slug' => $project->slug,
					'image' => $media?->file ?? 'images/dummy-teaser-1.jpg',
					'orientation' => $media?->orientation ?? 'unknown',
				];
			});
	}
}
