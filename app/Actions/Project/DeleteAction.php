<?php

namespace App\Actions\Project;

use App\Actions\Media\DeleteAction as DeleteMediaAction;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class DeleteAction
{
	public function execute(Project $project): void
	{
		DB::transaction(function () use ($project): void {
			$deleteMedia = new DeleteMediaAction;

			foreach ($project->media as $media) {
				$deleteMedia->execute($media);
			}

			$project->delete();
		});
	}
}
