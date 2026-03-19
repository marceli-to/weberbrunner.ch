<?php

namespace App\Http\Controllers\Api;

use App\Actions\PublicationBlock\DeleteAction as DeletePublicationBlockAction;
use App\Actions\PublicationBlock\ReorderAction as ReorderPublicationBlockAction;
use App\Actions\PublicationBlock\StoreAction as StorePublicationBlockAction;
use App\Actions\PublicationBlock\UpdateAction as UpdatePublicationBlockAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PublicationBlock\ReorderPublicationBlockRequest;
use App\Http\Requests\PublicationBlock\StorePublicationBlockRequest;
use App\Http\Requests\PublicationBlock\UpdatePublicationBlockRequest;
use App\Http\Resources\PublicationBlockResource;
use App\Models\Publication;
use App\Models\PublicationBlock;

class PublicationBlockController extends Controller
{
	public function index(Publication $publication)
	{
		$this->authorize('view', $publication);

		return PublicationBlockResource::collection(
			$publication->blocks()->with(['media'])->orderBy('sort_order')->get()
		);
	}

	public function store(StorePublicationBlockRequest $request, Publication $publication)
	{
		$this->authorize('update', $publication);

		$block = (new StorePublicationBlockAction)->execute($publication, $request->validated());

		return new PublicationBlockResource($block->load(['media']));
	}

	public function update(UpdatePublicationBlockRequest $request, Publication $publication, PublicationBlock $block)
	{
		$this->authorize('update', $publication);

		$block = (new UpdatePublicationBlockAction)->execute($block, $request->validated());

		return new PublicationBlockResource($block->load(['media']));
	}

	public function destroy(Publication $publication, PublicationBlock $block)
	{
		$this->authorize('update', $publication);

		(new DeletePublicationBlockAction)->execute($block);

		return response()->json(null, 204);
	}

	public function reorder(ReorderPublicationBlockRequest $request, Publication $publication)
	{
		$this->authorize('update', $publication);

		(new ReorderPublicationBlockAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
