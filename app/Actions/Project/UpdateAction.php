<?php

namespace App\Actions\Project;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateAction
{
	public function execute(Project $project, array $data): Project
	{
		$hasCategories = array_key_exists('categories', $data);
		$hasStatuses = array_key_exists('statuses', $data);
		$media = $data['media'] ?? [];
		$categories = $data['categories'] ?? [];
		$statuses = $data['statuses'] ?? [];
		unset($data['media'], $data['categories'], $data['statuses']);

		$slugParts = [$data['title']];
		if (!empty($data['city'])) {
			$slugParts[] = $data['city'];
		}
		$data['slug'] = Str::slug(implode(' ', $slugParts));

		return DB::transaction(function () use ($project, $data, $hasCategories, $categories, $hasStatuses, $statuses, $media): Project {
			$project->update($data);

			if ($hasCategories) {
				$project->categories()->sync($categories);
			}

			if ($hasStatuses) {
				$project->statuses()->sync($statuses);
			}

			if (!empty($media)) {
				(new AttachMediaAction)->execute($media, $project);
			}

			return $project;
		});
	}
}
