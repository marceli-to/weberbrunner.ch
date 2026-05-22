<?php

namespace App\Http\Controllers\Api;

use App\Actions\Project\DeleteAction as DeleteProjectAction;
use App\Actions\Project\ReorderAction as ReorderProjectAction;
use App\Actions\Project\StoreAction as StoreProjectAction;
use App\Actions\Project\ToggleAction as ToggleProjectAction;
use App\Actions\Project\UpdateAction as UpdateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ReorderProjectRequest;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectListResource;
use App\Http\Resources\ProjectResource;
use App\Models\Project;

class ProjectController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Project::class);

		$projects = Project::query()
			->select(['uuid', 'priority', 'number', 'title', 'city'])
			->orderBy('number')
			->get();

		return ProjectListResource::collection($projects);
	}

	public function published()
	{
		$this->authorize('viewAny', Project::class);

		$projects = Project::published()->with('teaser')->orderBy('title')->get();

		return ProjectResource::collection($projects);
	}

	public function store(StoreProjectRequest $request)
	{
		$this->authorize('create', Project::class);

		$project = (new StoreProjectAction)->execute($request->validated());

		return new ProjectResource($project->load(['masterdata', 'media', 'categories', 'statuses', 'location', 'blocks.media', 'blocks.links.linkedProject']));
	}

	public function show(Project $project)
	{
		$this->authorize('view', $project);

		$project->load(['masterdata', 'media', 'categories', 'statuses', 'location', 'blocks.media', 'blocks.links.linkedProject']);

		return new ProjectResource($project);
	}

	public function update(UpdateProjectRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		$project = (new UpdateProjectAction)->execute($project, $request->validated());

		return new ProjectResource($project->load(['masterdata', 'media', 'categories', 'statuses', 'location', 'blocks.media', 'blocks.links.linkedProject']));
	}

	public function destroy(Project $project)
	{
		$this->authorize('delete', $project);

		(new DeleteProjectAction)->execute($project);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$project = Project::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $project);

		$project->restore();

		return new ProjectResource($project->load(['masterdata', 'media', 'categories', 'statuses', 'location', 'blocks.media', 'blocks.links.linkedProject']));
	}

	public function toggle(Project $project)
	{
		$this->authorize('update', $project);

		(new ToggleProjectAction)->execute($project);

		return response()->json(null, 204);
	}

	public function reorder(ReorderProjectRequest $request)
	{
		$this->authorize('reorder', Project::class);

		(new ReorderProjectAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
