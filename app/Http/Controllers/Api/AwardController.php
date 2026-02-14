<?php

namespace App\Http\Controllers\Api;

use App\Actions\Award\DeleteAction as DeleteAwardAction;
use App\Actions\Award\ToggleAction as ToggleAwardAction;
use App\Actions\Award\ReorderAction as ReorderAwardAction;
use App\Actions\Award\StoreAction as StoreAwardAction;
use App\Actions\Award\UpdateAction as UpdateAwardAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Award\StoreAwardRequest;
use App\Http\Requests\Award\ReorderAwardRequest;
use App\Http\Requests\Award\UpdateAwardRequest;
use App\Http\Resources\AwardResource;
use App\Http\Resources\SectionResource;
use App\Models\Award;
use App\Models\Section;

class AwardController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Award::class);

		$sections = Section::query()
			->where('type', 'award')
			->orderBy('sort_order')
			->with(['awards' => fn ($q) => $q->orderBy('sort_order')])
			->get();

		$grouped = $sections->map(fn ($section) => [
			'section' => new SectionResource($section),
			'entries' => AwardResource::collection($section->awards),
		]);

		return response()->json(['data' => $grouped]);
	}

	public function store(StoreAwardRequest $request)
	{
		$this->authorize('create', Award::class);

		$award = (new StoreAwardAction)->execute($request->validated());

		return new AwardResource($award->load('section'));
	}

	public function show(Award $award)
	{
		$this->authorize('view', $award);

		$award->load('section');

		return new AwardResource($award);
	}

	public function update(UpdateAwardRequest $request, Award $award)
	{
		$this->authorize('update', $award);

		$award = (new UpdateAwardAction)->execute($award, $request->validated());

		return new AwardResource($award->load('section'));
	}

	public function toggle(Award $award)
	{
		$this->authorize('update', $award);

		(new ToggleAwardAction)->execute($award);

		return response()->json(null, 204);
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

		return new AwardResource($award->load('section'));
	}

	public function reorder(ReorderAwardRequest $request)
	{
		$this->authorize('create', Award::class);

		(new ReorderAwardAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
