<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectText\UpdateAction as UpdateProjectTextAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectText\UpdateProjectTextRequest;
use App\Models\Project;

class ProjectTextController extends Controller
{
	public function update(UpdateProjectTextRequest $request, Project $project)
	{
		(new UpdateProjectTextAction)->execute($project, $request->validated());

		return response()->json(null, 204);
	}
}
