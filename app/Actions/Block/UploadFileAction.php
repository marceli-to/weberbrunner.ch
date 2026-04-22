<?php

namespace App\Actions\Block;

use App\Models\Block;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadFileAction
{
	public function execute(UploadedFile $file, Block $block): void
	{
		$name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
		$extension = Str::lower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
		$filename = $name . '-' . Str::random(6) . '.' . $extension;

		$file->storeAs('uploads', $filename, 'public');

		$block->media()->create([
			'uuid' => Str::uuid()->toString(),
			'file' => 'uploads/' . $filename,
			'original_name' => $file->getClientOriginalName(),
			'mime_type' => $file->getMimeType(),
			'size' => $file->getSize(),
			'sort_order' => ($block->media()->max('sort_order') ?? -1) + 1,
		]);
	}
}
