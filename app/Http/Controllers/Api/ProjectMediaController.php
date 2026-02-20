<?php

namespace App\Http\Controllers\Api;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\AttachMediaRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;

class ProjectMediaController extends Controller
{
	public function attach(AttachMediaRequest $request, Project $project)
	{
		(new AttachMediaAction)->execute($request->validated('media'), $project);

		return new ProjectResource($project->load(['attributes', 'media', 'categories', 'statuses', 'location', 'blocks.media', 'blocks.links.linkedProject']));
	}
}
