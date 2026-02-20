<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProjectBlockLink\DeleteAction as DeleteProjectBlockLinkAction;
use App\Actions\ProjectBlockLink\ReorderAction as ReorderProjectBlockLinkAction;
use App\Actions\ProjectBlockLink\StoreAction as StoreProjectBlockLinkAction;
use App\Actions\ProjectBlockLink\UpdateAction as UpdateProjectBlockLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectBlockLink\ReorderProjectBlockLinkRequest;
use App\Http\Requests\ProjectBlockLink\StoreProjectBlockLinkRequest;
use App\Http\Requests\ProjectBlockLink\UpdateProjectBlockLinkRequest;
use App\Http\Resources\ProjectBlockLinkResource;
use App\Models\Project;
use App\Models\ProjectBlock;
use App\Models\ProjectBlockLink;

class ProjectBlockLinkController extends Controller
{
	public function store(StoreProjectBlockLinkRequest $request, Project $project, ProjectBlock $block)
	{
		$this->authorize('update', $project);

		$link = (new StoreProjectBlockLinkAction)->execute($block, $request->validated());

		return new ProjectBlockLinkResource($link->load('linkedProject'));
	}

	public function update(UpdateProjectBlockLinkRequest $request, Project $project, ProjectBlock $block, ProjectBlockLink $link)
	{
		$this->authorize('update', $project);

		$link = (new UpdateProjectBlockLinkAction)->execute($link, $request->validated());

		return new ProjectBlockLinkResource($link->load('linkedProject'));
	}

	public function destroy(Project $project, ProjectBlock $block, ProjectBlockLink $link)
	{
		$this->authorize('update', $project);

		(new DeleteProjectBlockLinkAction)->execute($link);

		return response()->json(null, 204);
	}

	public function reorder(ReorderProjectBlockLinkRequest $request, Project $project, ProjectBlock $block)
	{
		$this->authorize('update', $project);

		(new ReorderProjectBlockLinkAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
