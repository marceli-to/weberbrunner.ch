<?php

namespace App\Http\Controllers\Api;

use App\Actions\Status\DeleteAction as DeleteStatusAction;
use App\Actions\Status\ReorderAction as ReorderStatusAction;
use App\Actions\Status\StoreAction as StoreStatusAction;
use App\Actions\Status\UpdateAction as UpdateStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Status\StoreStatusRequest;
use App\Http\Requests\Status\UpdateStatusRequest;
use App\Http\Resources\StatusResource;
use App\Models\Status;

class StatusController extends Controller
{
	public function index()
	{
		$statuses = Status::orderBy('sort_order')->get();

		return StatusResource::collection($statuses);
	}

	public function store(StoreStatusRequest $request)
	{
		$status = (new StoreStatusAction)->execute($request->validated());

		return new StatusResource($status);
	}

	public function show(Status $status)
	{
		return new StatusResource($status);
	}

	public function update(UpdateStatusRequest $request, Status $status)
	{
		$status = (new UpdateStatusAction)->execute($status, $request->validated());

		return new StatusResource($status);
	}

	public function destroy(Status $status)
	{
		(new DeleteStatusAction)->execute($status);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$status = Status::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$status->restore();

		return new StatusResource($status);
	}

	public function reorder()
	{
		(new ReorderStatusAction)->execute(request('items'));

		return response()->json(null, 204);
	}
}
