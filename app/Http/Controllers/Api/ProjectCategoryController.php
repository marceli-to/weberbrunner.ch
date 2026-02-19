<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectCategory\SyncAction as SyncProjectCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectCategory\SyncProjectCategoryRequest;
use App\Models\Project;

class ProjectCategoryController extends Controller
{
	public function sync(SyncProjectCategoryRequest $request, Project $project)
	{
		(new SyncProjectCategoryAction)->execute($project, $request->validated('categories', []));

		return response()->json(null, 204);
	}
}
