<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectLink\DeleteAction as DeleteProjectLinkAction;
use App\Actions\ProjectLink\ReorderAction as ReorderProjectLinkAction;
use App\Actions\ProjectLink\StoreAction as StoreProjectLinkAction;
use App\Actions\ProjectLink\UpdateAction as UpdateProjectLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectLink\StoreProjectLinkRequest;
use App\Http\Requests\ProjectLink\ReorderProjectLinkRequest;
use App\Http\Requests\ProjectLink\UpdateProjectLinkRequest;
use App\Http\Resources\ProjectLinkResource;
use App\Models\Project;
use App\Models\ProjectLink;

class ProjectLinkController extends Controller
{
	public function index(Project $project)
	{
		$this->authorize('view', $project);

		return ProjectLinkResource::collection(
			$project->links()->orderBy('sort_order')->get()
		);
	}

	public function store(StoreProjectLinkRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		$link = (new StoreProjectLinkAction)->execute($project, $request->validated());

		return new ProjectLinkResource($link);
	}

	public function update(UpdateProjectLinkRequest $request, Project $project, ProjectLink $link)
	{
		$this->authorize('update', $project);

		$link = (new UpdateProjectLinkAction)->execute($link, $request->validated());

		return new ProjectLinkResource($link);
	}

	public function destroy(Project $project, ProjectLink $link)
	{
		$this->authorize('update', $project);

		(new DeleteProjectLinkAction)->execute($link);

		return response()->json(null, 204);
	}

	public function reorder(ReorderProjectLinkRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		(new ReorderProjectLinkAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
