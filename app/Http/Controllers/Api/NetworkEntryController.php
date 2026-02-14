<?php

namespace App\Http\Controllers\Api;

use App\Actions\NetworkEntry\DeleteAction as DeleteNetworkEntryAction;
use App\Actions\NetworkEntry\ToggleAction as ToggleNetworkEntryAction;
use App\Actions\NetworkEntry\ReorderAction as ReorderNetworkEntryAction;
use App\Actions\NetworkEntry\StoreAction as StoreNetworkEntryAction;
use App\Actions\NetworkEntry\UpdateAction as UpdateNetworkEntryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\NetworkEntry\StoreNetworkEntryRequest;
use App\Http\Requests\NetworkEntry\ReorderNetworkEntryRequest;
use App\Http\Requests\NetworkEntry\UpdateNetworkEntryRequest;
use App\Http\Resources\NetworkEntryResource;
use App\Http\Resources\SectionResource;
use App\Models\NetworkEntry;
use App\Models\Section;

class NetworkEntryController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', NetworkEntry::class);

		$sections = Section::query()
			->where('type', 'network')
			->orderBy('sort_order')
			->with(['networkEntries' => fn ($q) => $q->orderBy('sort_order')])
			->get();

		$grouped = $sections->map(fn ($section) => [
			'section' => new SectionResource($section),
			'entries' => NetworkEntryResource::collection($section->networkEntries),
		]);

		return response()->json(['data' => $grouped]);
	}

	public function store(StoreNetworkEntryRequest $request)
	{
		$this->authorize('create', NetworkEntry::class);

		$entry = (new StoreNetworkEntryAction)->execute($request->validated());

		return new NetworkEntryResource($entry->load('section'));
	}

	public function show(NetworkEntry $networkEntry)
	{
		$this->authorize('view', $networkEntry);

		$networkEntry->load('section');

		return new NetworkEntryResource($networkEntry);
	}

	public function update(UpdateNetworkEntryRequest $request, NetworkEntry $networkEntry)
	{
		$this->authorize('update', $networkEntry);

		$entry = (new UpdateNetworkEntryAction)->execute($networkEntry, $request->validated());

		return new NetworkEntryResource($entry->load('section'));
	}

	public function toggle(NetworkEntry $networkEntry)
	{
		$this->authorize('update', $networkEntry);

		(new ToggleNetworkEntryAction)->execute($networkEntry);

		return response()->json(null, 204);
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

		return new NetworkEntryResource($entry->load('section'));
	}

	public function reorder(ReorderNetworkEntryRequest $request)
	{
		$this->authorize('create', NetworkEntry::class);

		(new ReorderNetworkEntryAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
