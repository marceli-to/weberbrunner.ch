<?php

namespace App\Http\Controllers\Api;

use App\Actions\Award\DeleteAction as DeleteAwardAction;
use App\Actions\Award\ReorderAction as ReorderAwardAction;
use App\Actions\Award\StoreAction as StoreAwardAction;
use App\Actions\Award\UpdateAction as UpdateAwardAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Award\StoreAwardRequest;
use App\Http\Requests\Award\ReorderAwardRequest;
use App\Http\Requests\Award\UpdateAwardRequest;
use App\Http\Resources\AwardResource;
use App\Models\Award;

class AwardController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Award::class);

		$awards = Award::with('project')->orderBy('sort_order')->get();

		return AwardResource::collection($awards);
	}

	public function store(StoreAwardRequest $request)
	{
		$this->authorize('create', Award::class);

		$award = (new StoreAwardAction)->execute($request->validated());

		return new AwardResource($award->load('project'));
	}

	public function show(Award $award)
	{
		$this->authorize('view', $award);

		$award->load('project');

		return new AwardResource($award);
	}

	public function update(UpdateAwardRequest $request, Award $award)
	{
		$this->authorize('update', $award);

		$award = (new UpdateAwardAction)->execute($award, $request->validated());

		return new AwardResource($award->load('project'));
	}

	public function destroy(Award $award)
	{
		$this->authorize('delete', $award);

		(new DeleteAwardAction)->execute($award);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$award = Award::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $award);

		$award->restore();

		return new AwardResource($award->load('project'));
	}

	public function reorder(ReorderAwardRequest $request)
	{
		$this->authorize('create', Award::class);

		(new ReorderAwardAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
