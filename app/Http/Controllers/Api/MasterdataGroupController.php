<?php

namespace App\Http\Controllers\Api;

use App\Actions\MasterdataGroup\DeleteAction as DeleteMasterdataGroupAction;
use App\Actions\MasterdataGroup\ReorderAction as ReorderMasterdataGroupAction;
use App\Actions\MasterdataGroup\StoreAction as StoreMasterdataGroupAction;
use App\Actions\MasterdataGroup\UpdateAction as UpdateMasterdataGroupAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterdataGroup\StoreMasterdataGroupRequest;
use App\Http\Requests\MasterdataGroup\ReorderMasterdataGroupRequest;
use App\Http\Requests\MasterdataGroup\UpdateMasterdataGroupRequest;
use App\Http\Resources\MasterdataGroupResource;
use App\Models\MasterdataGroup;

class MasterdataGroupController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', MasterdataGroup::class);

		return MasterdataGroupResource::collection(
			MasterdataGroup::orderBy('sort_order')->get()
		);
	}

	public function store(StoreMasterdataGroupRequest $request)
	{
		$this->authorize('create', MasterdataGroup::class);

		$group = (new StoreMasterdataGroupAction)->execute($request->validated());

		return new MasterdataGroupResource($group);
	}

	public function show(MasterdataGroup $masterdataGroup)
	{
		$this->authorize('view', $masterdataGroup);

		return new MasterdataGroupResource($masterdataGroup);
	}

	public function update(UpdateMasterdataGroupRequest $request, MasterdataGroup $masterdataGroup)
	{
		$this->authorize('update', $masterdataGroup);

		$group = (new UpdateMasterdataGroupAction)->execute($masterdataGroup, $request->validated());

		return new MasterdataGroupResource($group);
	}

	public function destroy(MasterdataGroup $masterdataGroup)
	{
		$this->authorize('delete', $masterdataGroup);

		(new DeleteMasterdataGroupAction)->execute($masterdataGroup);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$group = MasterdataGroup::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $group);

		$group->restore();

		return new MasterdataGroupResource($group);
	}

	public function reorder(ReorderMasterdataGroupRequest $request)
	{
		$this->authorize('reorder', MasterdataGroup::class);

		(new ReorderMasterdataGroupAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
