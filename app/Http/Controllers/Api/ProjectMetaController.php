<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectMeta\UpdateAction as UpdateProjectMetaAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMeta\UpdateProjectMetaRequest;
use App\Models\Project;

class ProjectMetaController extends Controller
{
	public function update(UpdateProjectMetaRequest $request, Project $project)
	{
		(new UpdateProjectMetaAction)->execute($project, $request->validated());

		return response()->json(null, 204);
	}
}
