<?php

namespace App\Http\Controllers\Api;

use App\Actions\Jury\DeleteAction as DeleteJuryAction;
use App\Actions\Jury\ToggleAction as ToggleJuryAction;
use App\Actions\Jury\ReorderAction as ReorderJuryAction;
use App\Actions\Jury\StoreAction as StoreJuryAction;
use App\Actions\Jury\UpdateAction as UpdateJuryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Jury\StoreJuryRequest;
use App\Http\Requests\Jury\ReorderJuryRequest;
use App\Http\Requests\Jury\UpdateJuryRequest;
use App\Http\Resources\JuryResource;
use App\Http\Resources\SectionResource;
use App\Models\Jury;
use App\Models\Section;

class JuryController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Jury::class);

		$sections = Section::query()
			->where('type', 'jury')
			->orderBy('sort_order')
			->with(['juries' => fn ($q) => $q->orderBy('sort_order')])
			->get();

		$grouped = $sections->map(fn ($section) => [
			'section' => new SectionResource($section),
			'entries' => JuryResource::collection($section->juries),
		]);

		return response()->json(['data' => $grouped]);
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

	public function toggle(Jury $jury)
	{
		$this->authorize('update', $jury);

		(new ToggleJuryAction)->execute($jury);

		return response()->json(null, 204);
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
