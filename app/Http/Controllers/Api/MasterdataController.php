<?php

namespace App\Http\Controllers\Api;

use App\Actions\Masterdata\DeleteAction as DeleteMasterdataAction;
use App\Actions\Masterdata\ListAction as ListMasterdataAction;
use App\Actions\Masterdata\ReorderAction as ReorderMasterdataAction;
use App\Actions\Masterdata\StoreAction as StoreMasterdataAction;
use App\Actions\Masterdata\ToggleStandardAction as ToggleMasterdataStandardAction;
use App\Actions\Masterdata\UpdateAction as UpdateMasterdataAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Masterdata\StoreMasterdataRequest;
use App\Http\Requests\Masterdata\ReorderMasterdataRequest;
use App\Http\Requests\Masterdata\UpdateMasterdataRequest;
use App\Http\Resources\MasterdataGroupResource;
use App\Http\Resources\MasterdataResource;
use App\Models\Masterdata;

class MasterdataController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Masterdata::class);

		$groups = (new ListMasterdataAction)->execute();

		$grouped = $groups->map(fn ($group) => [
			'section' => new MasterdataGroupResource($group),
			'entries' => MasterdataResource::collection($group->masterdata),
		]);

		return response()->json(['data' => $grouped]);
	}

	public function store(StoreMasterdataRequest $request)
	{
		$this->authorize('create', Masterdata::class);

		$masterdata = (new StoreMasterdataAction)->execute($request->validated());

		return new MasterdataResource($masterdata->load('masterdataGroup'));
	}

	public function show(Masterdata $masterdata)
	{
		$this->authorize('view', $masterdata);

		return new MasterdataResource($masterdata->load('masterdataGroup'));
	}

	public function update(UpdateMasterdataRequest $request, Masterdata $masterdata)
	{
		$this->authorize('update', $masterdata);

		$masterdata = (new UpdateMasterdataAction)->execute($masterdata, $request->validated());

		return new MasterdataResource($masterdata->load('masterdataGroup'));
	}

	public function toggleStandard(Masterdata $masterdata)
	{
		$this->authorize('update', $masterdata);

		(new ToggleMasterdataStandardAction)->execute($masterdata);

		return response()->json(null, 204);
	}

	public function destroy(Masterdata $masterdata)
	{
		$this->authorize('delete', $masterdata);

		(new DeleteMasterdataAction)->execute($masterdata);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$masterdata = Masterdata::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $masterdata);

		$masterdata->restore();

		return new MasterdataResource($masterdata->load('masterdataGroup'));
	}

	public function reorder(ReorderMasterdataRequest $request)
	{
		$this->authorize('reorder', Masterdata::class);

		(new ReorderMasterdataAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
