<?php

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteAction
{
	public function execute(Media $media): void
	{
		$file = $media->file;

		DB::transaction(function () use ($media, $file): void {
			$media->delete();

			DB::afterCommit(function () use ($file): void {
				Storage::disk('public')->delete($file);
			});
		});
	}
}
