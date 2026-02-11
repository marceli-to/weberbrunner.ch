<?php

namespace App\Http\Controllers\Api;

use App\Actions\Location\DeleteAction as DeleteLocationAction;
use App\Actions\Location\ReorderAction as ReorderLocationAction;
use App\Actions\Location\StoreAction as StoreLocationAction;
use App\Actions\Location\UpdateAction as UpdateLocationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;

class LocationController extends Controller
{
	public function index()
	{
		$locations = Location::orderBy('sort_order')->get();

		return LocationResource::collection($locations);
	}

	public function store(StoreLocationRequest $request)
	{
		$location = (new StoreLocationAction)->execute($request->validated());

		return new LocationResource($location);
	}

	public function show(Location $location)
	{
		return new LocationResource($location);
	}

	public function update(UpdateLocationRequest $request, Location $location)
	{
		$location = (new UpdateLocationAction)->execute($location, $request->validated());

		return new LocationResource($location);
	}

	public function destroy(Location $location)
	{
		(new DeleteLocationAction)->execute($location);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$location = Location::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$location->restore();

		return new LocationResource($location);
	}

	public function reorder()
	{
		(new ReorderLocationAction)->execute(request('items'));

		return response()->json(null, 204);
	}
}
