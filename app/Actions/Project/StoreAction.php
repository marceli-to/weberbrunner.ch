<?php

namespace App\Actions\Project;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Models\Project;
use Illuminate\Support\Str;

class StoreAction
{
	public function execute(array $data): Project
	{
		$media = $data['media'] ?? [];
		$categories = $data['categories'] ?? [];
		$statuses = $data['statuses'] ?? [];
		unset($data['media'], $data['categories'], $data['statuses']);

		$data['slug'] = Str::slug($data['title']);

		$project = Project::create($data);

		if (!empty($categories)) {
			$project->categories()->sync($categories);
		}

		if (!empty($statuses)) {
			$project->statuses()->sync($statuses);
		}

		if (!empty($media)) {
			(new AttachMediaAction)->execute($media, $project);
		}

		return $project;
	}
}
