<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectBlock\SelectMediaAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectBlockResource;
use App\Models\Media;
use App\Models\Project;
use App\Models\ProjectBlock;
use Illuminate\Http\Request;

class ProjectBlockMediaController extends Controller
{
	public function select(Request $request, Project $project, ProjectBlock $block)
	{
		$this->authorize('update', $project);

		$request->validate([
			'media_uuids' => 'required|array',
			'media_uuids.*' => 'required|string|exists:media,uuid',
		]);

		(new SelectMediaAction)->execute($block, $request->input('media_uuids'));

		return new ProjectBlockResource($block->load(['media', 'links.linkedProject']));
	}

	public function detach(Project $project, ProjectBlock $block, Media $media)
	{
		$this->authorize('update', $project);

		$media->delete();

		return response()->json(null, 204);
	}
}
