<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectMasterdata\AttachAction as AttachProjectMasterdataAction;
use App\Actions\ProjectMasterdata\DestroyAction as DestroyProjectMasterdataAction;
use App\Actions\ProjectMasterdata\ListAllAction as ListAllProjectMasterdataAction;
use App\Actions\ProjectMasterdata\ListAttachedAction as ListAttachedProjectMasterdataAction;
use App\Actions\ProjectMasterdata\ListAvailableAction as ListAvailableProjectMasterdataAction;
use App\Actions\ProjectMasterdata\ReorderAction as ReorderProjectMasterdataAction;
use App\Actions\ProjectMasterdata\UpdateValuesAction as UpdateProjectMasterdataValuesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMasterdata\ReorderProjectMasterdataRequest;
use App\Http\Requests\ProjectMasterdata\UpdateProjectMasterdataValuesRequest;
use App\Http\Resources\ProjectMasterdataResource;
use App\Models\Masterdata;
use App\Models\Project;

class ProjectMasterdataController extends Controller
{
	public function all(Project $project)
	{
		$this->authorize('view', $project);

		$entries = (new ListAllProjectMasterdataAction)->execute($project);

		return ProjectMasterdataResource::collection($entries);
	}

	public function attached(Project $project)
	{
		$this->authorize('view', $project);

		$entries = (new ListAttachedProjectMasterdataAction)->execute($project);

		return ProjectMasterdataResource::collection($entries);
	}

	public function available(Project $project)
	{
		$this->authorize('view', $project);

		$entries = (new ListAvailableProjectMasterdataAction)->execute($project);

		return ProjectMasterdataResource::collection($entries);
	}

	public function updateValues(UpdateProjectMasterdataValuesRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		(new UpdateProjectMasterdataValuesAction)->execute($project, $request->validated('entries', []));

		return response()->json(null, 204);
	}

	public function reorder(ReorderProjectMasterdataRequest $request, Project $project)
	{
		$this->authorize('update', $project);

		(new ReorderProjectMasterdataAction)->execute($project, $request->validated('items'));

		return response()->json(null, 204);
	}

	public function attach(Project $project, Masterdata $masterdata)
	{
		$this->authorize('update', $project);

		(new AttachProjectMasterdataAction)->execute($project, $masterdata);

		return response()->json(null, 204);
	}

	public function destroy(Project $project, Masterdata $masterdata)
	{
		$this->authorize('update', $project);

		(new DestroyProjectMasterdataAction)->execute($project, $masterdata);

		return response()->json(null, 204);
	}
}
