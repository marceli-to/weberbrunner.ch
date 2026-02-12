<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectAttribute\DeleteAction as DeleteProjectAttributeAction;
use App\Actions\ProjectAttribute\ReorderAction as ReorderProjectAttributeAction;
use App\Actions\ProjectAttribute\StoreAction as StoreProjectAttributeAction;
use App\Actions\ProjectAttribute\UpdateAction as UpdateProjectAttributeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectAttribute\StoreProjectAttributeRequest;
use App\Http\Requests\ProjectAttribute\ReorderProjectAttributeRequest;
use App\Http\Requests\ProjectAttribute\UpdateProjectAttributeRequest;
use App\Http\Resources\ProjectAttributeResource;
use App\Models\Project;
use App\Models\ProjectAttribute;

class ProjectAttributeController extends Controller
{
	public function index(Project $project)
	{
		$this->authorize('view', $project);

		return ProjectAttributeResource::collection(
			$project->attributes()->orderBy('sort_order')->get()
		);
	}

	public function store(StoreProjectAttributeRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		$attribute = (new StoreProjectAttributeAction)->execute($project, $request->validated());

		return new ProjectAttributeResource($attribute);
	}

	public function update(UpdateProjectAttributeRequest $request, Project $project, ProjectAttribute $attribute)
	{
		$this->authorize('update', $project);

		$attribute = (new UpdateProjectAttributeAction)->execute($attribute, $request->validated());

		return new ProjectAttributeResource($attribute);
	}

	public function destroy(Project $project, ProjectAttribute $attribute)
	{
		$this->authorize('update', $project);

		(new DeleteProjectAttributeAction)->execute($attribute);

		return response()->json(null, 204);
	}

	public function reorder(ReorderProjectAttributeRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		(new ReorderProjectAttributeAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
