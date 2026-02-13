<?php

namespace App\Http\Controllers\Api;

use App\Actions\Jury\DeleteAction as DeleteJuryAction;
use App\Actions\Jury\ReorderAction as ReorderJuryAction;
use App\Actions\Jury\StoreAction as StoreJuryAction;
use App\Actions\Jury\UpdateAction as UpdateJuryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Jury\StoreJuryRequest;
use App\Http\Requests\Jury\ReorderJuryRequest;
use App\Http\Requests\Jury\UpdateJuryRequest;
use App\Http\Resources\JuryResource;
use App\Models\Jury;

class JuryController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Jury::class);

		$juries = Jury::with('section')
			->join('sections', 'juries.section_id', '=', 'sections.id')
			->orderBy('sections.sort_order')
			->orderBy('juries.sort_order')
			->select('juries.*')
			->get();

		return JuryResource::collection($juries);
	}

	public function store(StoreJuryRequest $request)
	{
		$this->authorize('create', Jury::class);

		$jury = (new StoreJuryAction)->execute($request->validated());

		return new JuryResource($jury->load('section'));
	}

	public function show(Jury $jury)
	{
		$this->authorize('view', $jury);

		return new JuryResource($jury->load('section'));
	}

	public function update(UpdateJuryRequest $request, Jury $jury)
	{
		$this->authorize('update', $jury);

		$jury = (new UpdateJuryAction)->execute($jury, $request->validated());

		return new JuryResource($jury->load('section'));
	}

	public function destroy(Jury $jury)
	{
		$this->authorize('delete', $jury);

		(new DeleteJuryAction)->execute($jury);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$jury = Jury::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $jury);

		$jury->restore();

		return new JuryResource($jury->load('section'));
	}

	public function reorder(ReorderJuryRequest $request)
	{
		$this->authorize('create', Jury::class);

		(new ReorderJuryAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
