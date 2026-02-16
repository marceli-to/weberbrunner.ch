<?php

namespace App\Actions\Project;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Models\Project;
use Illuminate\Support\Str;

class UpdateAction
{
	public function execute(Project $project, array $data): Project
	{
		$media = $data['media'] ?? [];
		$categories = $data['categories'] ?? [];
		$statuses = $data['statuses'] ?? [];
		unset($data['media'], $data['categories'], $data['statuses']);

		$slugParts = [$data['title']];
		if (!empty($data['city'])) {
			$slugParts[] = $data['city'];
		}
		$data['slug'] = Str::slug(implode(' ', $slugParts));

		$project->update($data);

		if (array_key_exists('categories', $data) || !empty($categories)) {
			$project->categories()->sync($categories);
		}

		if (array_key_exists('statuses', $data) || !empty($statuses)) {
			$project->statuses()->sync($statuses);
		}

		if (!empty($media)) {
			(new AttachMediaAction)->execute($media, $project);
		}

		return $project;
	}
}
