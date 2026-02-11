<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectAttribute\DeleteAction as DeleteProjectAttributeAction;
use App\Actions\ProjectAttribute\ReorderAction as ReorderProjectAttributeAction;
use App\Actions\ProjectAttribute\StoreAction as StoreProjectAttributeAction;
use App\Actions\ProjectAttribute\UpdateAction as UpdateProjectAttributeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectAttribute\StoreProjectAttributeRequest;
use App\Http\Requests\ProjectAttribute\UpdateProjectAttributeRequest;
use App\Http\Resources\ProjectAttributeResource;
use App\Models\Project;
use App\Models\ProjectAttribute;

class ProjectAttributeController extends Controller
{
	public function index(Project $project)
	{
		return ProjectAttributeResource::collection(
			$project->attributes()->orderBy('sort_order')->get()
		);
	}

	public function store(StoreProjectAttributeRequest $request, Project $project)
	{
		$attribute = (new StoreProjectAttributeAction)->execute($project, $request->validated());

		return new ProjectAttributeResource($attribute);
	}

	public function update(UpdateProjectAttributeRequest $request, Project $project, ProjectAttribute $attribute)
	{
		$attribute = (new UpdateProjectAttributeAction)->execute($attribute, $request->validated());

		return new ProjectAttributeResource($attribute);
	}

	public function destroy(Project $project, ProjectAttribute $attribute)
	{
		(new DeleteProjectAttributeAction)->execute($attribute);

		return response()->json(null, 204);
	}

	public function reorder(Project $project)
	{
		(new ReorderProjectAttributeAction)->execute(request('items'));

		return response()->json(null, 204);
	}
}
