<?php

namespace App\Http\Controllers\Api;

use App\Actions\Block\SelectMediaAction;
use App\Actions\Block\UploadFileAction as UploadBlockFileAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlockResource;
use App\Models\Block;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BlockMediaController extends Controller
{
	public function select(Request $request, Model $parent, Block $block)
	{
		$this->authorize('update', $parent);

		$request->validate([
			'media_uuids' => 'required|array',
			'media_uuids.*' => 'required|string|exists:media,uuid',
		]);

		(new SelectMediaAction)->execute($block, $request->input('media_uuids'));

		return new BlockResource($block->load(['media', 'links.linkedProject']));
	}

	public function upload(Request $request, Model $parent, Block $block)
	{
		$this->authorize('update', $parent);

		$request->validate([
			'file' => 'required|file|max:51200',
		]);

		(new UploadBlockFileAction)->execute($request->file('file'), $block);

		return response()->json(null, 204);
	}

	public function detach(Request $request, Model $parent, Block $block, Media $media)
	{
		$this->authorize('update', $parent);

		$media->delete();

		return response()->json(null, 204);
	}
}
