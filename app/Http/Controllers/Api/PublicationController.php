<?php

namespace App\Http\Controllers\Api;

use App\Actions\Publication\DeleteAction as DeletePublicationAction;
use App\Actions\Publication\ReorderAction as ReorderPublicationAction;
use App\Actions\Publication\StoreAction as StorePublicationAction;
use App\Actions\Publication\ToggleAction as TogglePublicationAction;
use App\Actions\Publication\UpdateAction as UpdatePublicationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Publication\ReorderPublicationRequest;
use App\Http\Requests\Publication\StorePublicationRequest;
use App\Http\Requests\Publication\UpdatePublicationRequest;
use App\Http\Resources\PublicationResource;
use App\Models\Publication;

class PublicationController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Publication::class);

		$publications = Publication::with(['teaser', 'location'])
			->orderBy('sort_order')
			->get();

		return PublicationResource::collection($publications);
	}

	public function store(StorePublicationRequest $request)
	{
		$this->authorize('create', Publication::class);

		$publication = (new StorePublicationAction)->execute($request->validated());

		return new PublicationResource($publication->load(['location', 'attributes', 'blocks.media', 'media', 'teaser', 'og']));
	}

	public function show(Publication $publication)
	{
		$this->authorize('view', $publication);

		$publication->load(['location', 'attributes', 'blocks.media', 'media', 'teaser', 'og']);

		return new PublicationResource($publication);
	}

	public function update(UpdatePublicationRequest $request, Publication $publication)
	{
		$this->authorize('update', $publication);

		$publication = (new UpdatePublicationAction)->execute($publication, $request->validated());

		return new PublicationResource($publication->load(['location', 'attributes', 'blocks.media', 'media', 'teaser', 'og']));
	}

	public function destroy(Publication $publication)
	{
		$this->authorize('delete', $publication);

		(new DeletePublicationAction)->execute($publication);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$publication = Publication::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $publication);

		$publication->restore();

		return new PublicationResource($publication->load(['location', 'attributes', 'blocks.media', 'media', 'teaser', 'og']));
	}

	public function toggle(Publication $publication)
	{
		$this->authorize('update', $publication);

		(new TogglePublicationAction)->execute($publication);

		return response()->json(null, 204);
	}

	public function reorder(ReorderPublicationRequest $request)
	{
		$this->authorize('reorder', Publication::class);

		(new ReorderPublicationAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
