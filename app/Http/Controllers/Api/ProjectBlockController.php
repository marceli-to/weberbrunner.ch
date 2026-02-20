<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectBlock\DeleteAction as DeleteProjectBlockAction;
use App\Actions\ProjectBlock\ReorderAction as ReorderProjectBlockAction;
use App\Actions\ProjectBlock\StoreAction as StoreProjectBlockAction;
use App\Actions\ProjectBlock\UpdateAction as UpdateProjectBlockAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectBlock\ReorderProjectBlockRequest;
use App\Http\Requests\ProjectBlock\StoreProjectBlockRequest;
use App\Http\Requests\ProjectBlock\UpdateProjectBlockRequest;
use App\Http\Resources\ProjectBlockResource;
use App\Models\Project;
use App\Models\ProjectBlock;

class ProjectBlockController extends Controller
{
	public function index(Project $project)
	{
		$this->authorize('view', $project);

		return ProjectBlockResource::collection(
			$project->blocks()->with(['media', 'links.linkedProject'])->orderBy('sort_order')->get()
		);
	}

	public function store(StoreProjectBlockRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		$block = (new StoreProjectBlockAction)->execute($project, $request->validated());

		return new ProjectBlockResource($block->load(['media', 'links.linkedProject']));
	}

	public function update(UpdateProjectBlockRequest $request, Project $project, ProjectBlock $block)
	{
		$this->authorize('update', $project);

		$block = (new UpdateProjectBlockAction)->execute($block, $request->validated());

		return new ProjectBlockResource($block->load(['media', 'links.linkedProject']));
	}

	public function destroy(Project $project, ProjectBlock $block)
	{
		$this->authorize('update', $project);

		(new DeleteProjectBlockAction)->execute($block);

		return response()->json(null, 204);
	}

	public function reorder(ReorderProjectBlockRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		(new ReorderProjectBlockAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
