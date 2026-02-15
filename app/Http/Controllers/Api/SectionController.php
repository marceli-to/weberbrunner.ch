<?php

namespace App\Http\Controllers\Api;

use App\Actions\Section\DeleteAction as DeleteSectionAction;
use App\Actions\Section\ReorderAction as ReorderSectionAction;
use App\Actions\Section\StoreAction as StoreSectionAction;
use App\Actions\Section\UpdateAction as UpdateSectionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Section\StoreSectionRequest;
use App\Http\Requests\Section\ReorderSectionRequest;
use App\Http\Requests\Section\UpdateSectionRequest;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
	public function index(Request $request)
	{
		$this->authorize('viewAny', Section::class);

		$query = Section::orderBy('sort_order');

		if ($request->has('type')) {
			$query->where('type', $request->input('type'));
		}

		return SectionResource::collection($query->get());
	}

	public function store(StoreSectionRequest $request)
	{
		$this->authorize('create', Section::class);

		$section = (new StoreSectionAction)->execute($request->validated());

		return new SectionResource($section);
	}

	public function show(Section $section)
	{
		$this->authorize('view', $section);

		return new SectionResource($section);
	}

	public function update(UpdateSectionRequest $request, Section $section)
	{
		$this->authorize('update', $section);

		$section = (new UpdateSectionAction)->execute($section, $request->validated());

		return new SectionResource($section);
	}

	public function destroy(Section $section)
	{
		$this->authorize('delete', $section);

		(new DeleteSectionAction)->execute($section);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$section = Section::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $section);

		$section->restore();

		return new SectionResource($section);
	}

	public function reorder(ReorderSectionRequest $request)
	{
		$this->authorize('update', Section::class);

		(new ReorderSectionAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
