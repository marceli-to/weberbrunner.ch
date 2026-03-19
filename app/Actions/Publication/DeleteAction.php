<?php

namespace App\Actions\Publication;

use App\Actions\Media\DeleteAction as DeleteMediaAction;
use App\Models\Publication;
use Illuminate\Support\Facades\DB;

class DeleteAction
{
	public function execute(Publication $publication): void
	{
		DB::transaction(function () use ($publication): void {
			$deleteMedia = new DeleteMediaAction;

			foreach ($publication->media as $media) {
				$deleteMedia->execute($media);
			}

			$publication->delete();
		});
	}
}
