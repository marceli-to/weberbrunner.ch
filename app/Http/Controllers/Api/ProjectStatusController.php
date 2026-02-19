<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectStatus\SyncAction as SyncProjectStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectStatus\SyncProjectStatusRequest;
use App\Models\Project;

class ProjectStatusController extends Controller
{
	public function sync(SyncProjectStatusRequest $request, Project $project)
	{
		(new SyncProjectStatusAction)->execute($project, $request->validated('statuses', []));

		return response()->json(null, 204);
	}
}
