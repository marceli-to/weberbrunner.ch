<?php

namespace App\Http\Controllers\Api;

use App\Actions\PublicationBlock\SelectMediaAction;
use App\Actions\PublicationBlock\UploadFileAction as UploadPublicationBlockFileAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\PublicationBlockResource;
use App\Models\Media;
use App\Models\Publication;
use App\Models\PublicationBlock;
use Illuminate\Http\Request;

class PublicationBlockMediaController extends Controller
{
	public function select(Request $request, Publication $publication, PublicationBlock $block)
	{
		$this->authorize('update', $publication);

		$request->validate([
			'media_uuids' => 'required|array',
			'media_uuids.*' => 'required|string|exists:media,uuid',
		]);

		(new SelectMediaAction)->execute($block, $request->input('media_uuids'));

		return new PublicationBlockResource($block->load(['media']));
	}

	public function uploadFile(Request $request, Publication $publication, PublicationBlock $block)
	{
		$this->authorize('update', $publication);

		$request->validate([
			'file' => 'required|file|max:51200',
		]);

		(new UploadPublicationBlockFileAction)->execute($request->file('file'), $block);

		return response()->json(null, 204);
	}

	public function detach(Publication $publication, PublicationBlock $block, Media $media)
	{
		$this->authorize('update', $publication);

		$media->delete();

		return response()->json(null, 204);
	}
}
