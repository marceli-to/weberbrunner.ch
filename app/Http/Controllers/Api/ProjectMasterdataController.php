<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectMasterdata\DestroyAction as DestroyProjectMasterdataAction;
use App\Actions\ProjectMasterdata\ListAction as ListProjectMasterdataAction;
use App\Actions\ProjectMasterdata\ListAttachedAction as ListAttachedProjectMasterdataAction;
use App\Actions\ProjectMasterdata\ReorderAction as ReorderProjectMasterdataAction;
use App\Actions\ProjectMasterdata\SyncAction as SyncProjectMasterdataAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMasterdata\ReorderProjectMasterdataRequest;
use App\Http\Requests\ProjectMasterdata\SyncProjectMasterdataRequest;
use App\Http\Resources\MasterdataGroupResource;
use App\Http\Resources\ProjectMasterdataResource;
use App\Models\Masterdata;
use App\Models\Project;

class ProjectMasterdataController extends Controller
{
	public function index(Project $project)
	{
		$this->authorize('view', $project);

		$groups = (new ListProjectMasterdataAction)->execute($project);

		$grouped = $groups->map(fn ($group) => [
			'section' => new MasterdataGroupResource($group),
			'entries' => ProjectMasterdataResource::collection($group->masterdata),
		]);

		return response()->json(['data' => $grouped]);
	}

	public function attached(Project $project)
	{
		$this->authorize('view', $project);

		$entries = (new ListAttachedProjectMasterdataAction)->execute($project);

		return ProjectMasterdataResource::collection($entries);
	}

	public function sync(SyncProjectMasterdataRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		(new SyncProjectMasterdataAction)->execute($project, $request->validated('entries', []));

		return response()->json(null, 204);
	}

	public function reorder(ReorderProjectMasterdataRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		(new ReorderProjectMasterdataAction)->execute($project, $request->validated('items'));

		return response()->json(null, 204);
	}

	public function destroy(Project $project, Masterdata $masterdata)
	{
		$this->authorize('update', $project);

		(new DestroyProjectMasterdataAction)->execute($project, $masterdata);

		return response()->json(null, 204);
	}
}
