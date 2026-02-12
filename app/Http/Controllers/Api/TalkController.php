<?php

namespace App\Http\Controllers\Api;

use App\Actions\Talk\DeleteAction as DeleteTalkAction;
use App\Actions\Talk\ReorderAction as ReorderTalkAction;
use App\Actions\Talk\StoreAction as StoreTalkAction;
use App\Actions\Talk\UpdateAction as UpdateTalkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Talk\StoreTalkRequest;
use App\Http\Requests\Talk\ReorderTalkRequest;
use App\Http\Requests\Talk\UpdateTalkRequest;
use App\Http\Resources\TalkResource;
use App\Models\Talk;

class TalkController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Talk::class);

		$talks = Talk::orderBy('sort_order')->get();

		return TalkResource::collection($talks);
	}

	public function store(StoreTalkRequest $request)
	{
		$this->authorize('create', Talk::class);

		$talk = (new StoreTalkAction)->execute($request->validated());

		return new TalkResource($talk);
	}

	public function show(Talk $talk)
	{
		$this->authorize('view', $talk);

		return new TalkResource($talk);
	}

	public function update(UpdateTalkRequest $request, Talk $talk)
	{
		$this->authorize('update', $talk);

		$talk = (new UpdateTalkAction)->execute($talk, $request->validated());

		return new TalkResource($talk);
	}

	public function destroy(Talk $talk)
	{
		$this->authorize('delete', $talk);

		(new DeleteTalkAction)->execute($talk);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$talk = Talk::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $talk);

		$talk->restore();

		return new TalkResource($talk);
	}

	public function reorder(ReorderTalkRequest $request)
	{
		$this->authorize('create', Talk::class);

		(new ReorderTalkAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
