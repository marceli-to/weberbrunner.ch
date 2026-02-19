<?php

namespace App\Http\Controllers\Api;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Actions\Project\DeleteAction as DeleteProjectAction;
use App\Actions\Project\ToggleAction as ToggleProjectAction;
use App\Actions\Project\ReorderAction as ReorderProjectAction;
use App\Actions\Project\StoreAction as StoreProjectAction;
use App\Actions\Project\UpdateAction as UpdateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\AttachMediaRequest;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\ReorderProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;

class ProjectController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Project::class);

		$query = Project::with(['attributes', 'media', 'categories', 'statuses', 'location'])
			->orderBy('number');

		if (request('search')) {
			$query->where(function ($q) {
				$q->where('title', 'like', '%' . request('search') . '%')
					->orWhere('description', 'like', '%' . request('search') . '%');
			});
		}

		if (request('category')) {
			$query->whereHas('categories', fn ($q) => $q->where('categories.id', request('category')));
		}

		if (request('status')) {
			$query->whereHas('statuses', fn ($q) => $q->where('statuses.id', request('status')));
		}

		if (request('location')) {
			$query->where('location_id', request('location'));
		}

		if (request()->has('publish')) {
			$query->where('publish', request()->boolean('publish'));
		}

		if (request()->boolean('trashed')) {
			$query->onlyTrashed();
		}

		return ProjectResource::collection($query->get());
	}

	public function store(StoreProjectRequest $request)
	{
		$this->authorize('create', Project::class);

		$project = (new StoreProjectAction)->execute($request->validated());

		return new ProjectResource($project->load(['attributes', 'media', 'categories', 'statuses', 'location']));
	}

	public function show(Project $project)
	{
		$this->authorize('view', $project);

		$project->load(['attributes', 'media', 'categories', 'statuses', 'location']);

		return new ProjectResource($project);
	}

	public function update(UpdateProjectRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		$project = (new UpdateProjectAction)->execute($project, $request->validated());

		return new ProjectResource($project->load(['attributes', 'media', 'categories', 'statuses', 'location']));
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

		return new ProjectResource($project->load(['attributes', 'media', 'categories', 'statuses', 'location']));
	}

	public function attachMedia(AttachMediaRequest $request, Project $project)
	{
		(new AttachMediaAction)->execute($request->validated('media'), $project);

		return new ProjectResource($project->load(['attributes', 'media', 'categories', 'statuses', 'location']));
	}

	public function syncCategories(Project $project)
	{
		$this->authorize('update', $project);

		$project->categories()->sync(request('categories', []));

		return response()->json(null, 204);
	}

	public function syncStatuses(Project $project)
	{
		$this->authorize('update', $project);

		$project->statuses()->sync(request('statuses', []));

		return response()->json(null, 204);
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
