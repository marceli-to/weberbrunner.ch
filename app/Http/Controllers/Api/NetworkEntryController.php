<?php

namespace App\Http\Controllers\Api;

use App\Actions\NetworkEntry\DeleteAction as DeleteNetworkEntryAction;
use App\Actions\NetworkEntry\ReorderAction as ReorderNetworkEntryAction;
use App\Actions\NetworkEntry\StoreAction as StoreNetworkEntryAction;
use App\Actions\NetworkEntry\UpdateAction as UpdateNetworkEntryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\NetworkEntry\StoreNetworkEntryRequest;
use App\Http\Requests\NetworkEntry\ReorderNetworkEntryRequest;
use App\Http\Requests\NetworkEntry\UpdateNetworkEntryRequest;
use App\Http\Resources\NetworkEntryResource;
use App\Models\NetworkEntry;

class NetworkEntryController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', NetworkEntry::class);

		$entries = NetworkEntry::with('media')->orderBy('sort_order')->get();

		return NetworkEntryResource::collection($entries);
	}

	public function store(StoreNetworkEntryRequest $request)
	{
		$this->authorize('create', NetworkEntry::class);

		$entry = (new StoreNetworkEntryAction)->execute($request->validated());

		return new NetworkEntryResource($entry->load('media'));
	}

	public function show(NetworkEntry $networkEntry)
	{
		$this->authorize('view', $networkEntry);

		$networkEntry->load('media');

		return new NetworkEntryResource($networkEntry);
	}

	public function update(UpdateNetworkEntryRequest $request, NetworkEntry $networkEntry)
	{
		$this->authorize('update', $networkEntry);

		$entry = (new UpdateNetworkEntryAction)->execute($networkEntry, $request->validated());

		return new NetworkEntryResource($entry->load('media'));
	}

	public function destroy(NetworkEntry $networkEntry)
	{
		$this->authorize('delete', $networkEntry);

		(new DeleteNetworkEntryAction)->execute($networkEntry);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$entry = NetworkEntry::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $entry);

		$entry->restore();

		return new NetworkEntryResource($entry->load('media'));
	}

	public function reorder(ReorderNetworkEntryRequest $request)
	{
		$this->authorize('create', NetworkEntry::class);

		(new ReorderNetworkEntryAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
