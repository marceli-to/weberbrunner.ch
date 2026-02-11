<?php

namespace App\Http\Controllers\Api;

use App\Actions\Jury\DeleteAction as DeleteJuryAction;
use App\Actions\Jury\ReorderAction as ReorderJuryAction;
use App\Actions\Jury\StoreAction as StoreJuryAction;
use App\Actions\Jury\UpdateAction as UpdateJuryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Jury\StoreJuryRequest;
use App\Http\Requests\Jury\UpdateJuryRequest;
use App\Http\Resources\JuryResource;
use App\Models\Jury;

class JuryController extends Controller
{
	public function index()
	{
		$juries = Jury::orderBy('sort_order')->get();

		return JuryResource::collection($juries);
	}

	public function store(StoreJuryRequest $request)
	{
		$jury = (new StoreJuryAction)->execute($request->validated());

		return new JuryResource($jury);
	}

	public function show(Jury $jury)
	{
		return new JuryResource($jury);
	}

	public function update(UpdateJuryRequest $request, Jury $jury)
	{
		$jury = (new UpdateJuryAction)->execute($jury, $request->validated());

		return new JuryResource($jury);
	}

	public function destroy(Jury $jury)
	{
		(new DeleteJuryAction)->execute($jury);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$jury = Jury::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$jury->restore();

		return new JuryResource($jury);
	}

	public function reorder()
	{
		(new ReorderJuryAction)->execute(request('items'));

		return response()->json(null, 204);
	}
}
