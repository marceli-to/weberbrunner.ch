<?php

namespace App\Http\Controllers\Api;

use App\Actions\LandingItem\DeleteAction as DeleteLandingItemAction;
use App\Actions\LandingItem\ListAction as ListLandingItemAction;
use App\Actions\LandingItem\ReorderAction as ReorderLandingItemAction;
use App\Actions\LandingItem\StoreAction as StoreLandingItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LandingItem\ReorderLandingItemRequest;
use App\Http\Requests\LandingItem\StoreLandingItemRequest;
use App\Http\Resources\LandingItemResource;
use App\Models\LandingItem;

class LandingItemController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', LandingItem::class);

		$grouped = (new ListLandingItemAction)->execute();

		return response()->json([
			'data' => [
				1 => LandingItemResource::collection($grouped[1]),
				2 => LandingItemResource::collection($grouped[2]),
				3 => LandingItemResource::collection($grouped[3]),
			],
		]);
	}

	public function store(StoreLandingItemRequest $request)
	{
		$this->authorize('create', LandingItem::class);

		$item = (new StoreLandingItemAction)->execute($request->validated());
		$item->load(['project' => fn ($q) => $q->with(['media' => fn ($q) => $q->where('is_teaser', true)])]);

		return new LandingItemResource($item);
	}

	public function destroy(LandingItem $landingItem)
	{
		$this->authorize('delete', $landingItem);

		(new DeleteLandingItemAction)->execute($landingItem);

		return response()->json(null, 204);
	}

	public function reorder(ReorderLandingItemRequest $request)
	{
		$this->authorize('reorder', LandingItem::class);

		(new ReorderLandingItemAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
