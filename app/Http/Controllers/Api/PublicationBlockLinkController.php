<?php

namespace App\Http\Controllers\Api;

use App\Actions\PublicationBlockLink\DeleteAction as DeletePublicationBlockLinkAction;
use App\Actions\PublicationBlockLink\ToggleAction as TogglePublicationBlockLinkAction;
use App\Actions\PublicationBlockLink\ReorderAction as ReorderPublicationBlockLinkAction;
use App\Actions\PublicationBlockLink\StoreAction as StorePublicationBlockLinkAction;
use App\Actions\PublicationBlockLink\UpdateAction as UpdatePublicationBlockLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PublicationBlockLink\ReorderPublicationBlockLinkRequest;
use App\Http\Requests\PublicationBlockLink\StorePublicationBlockLinkRequest;
use App\Http\Requests\PublicationBlockLink\UpdatePublicationBlockLinkRequest;
use App\Http\Resources\PublicationBlockLinkResource;
use App\Models\Publication;
use App\Models\PublicationBlock;
use App\Models\PublicationBlockLink;

class PublicationBlockLinkController extends Controller
{
	public function store(StorePublicationBlockLinkRequest $request, Publication $publication, PublicationBlock $block)
	{
		$this->authorize('update', $publication);

		$link = (new StorePublicationBlockLinkAction)->execute($block, $request->validated());

		return new PublicationBlockLinkResource($link->load('linkedProject'));
	}

	public function update(UpdatePublicationBlockLinkRequest $request, Publication $publication, PublicationBlock $block, PublicationBlockLink $link)
	{
		$this->authorize('update', $publication);

		$link = (new UpdatePublicationBlockLinkAction)->execute($link, $request->validated());

		return new PublicationBlockLinkResource($link->load('linkedProject'));
	}

	public function destroy(Publication $publication, PublicationBlock $block, PublicationBlockLink $link)
	{
		$this->authorize('update', $publication);

		(new DeletePublicationBlockLinkAction)->execute($link);

		return response()->json(null, 204);
	}

	public function toggle(Publication $publication, PublicationBlock $block, PublicationBlockLink $link)
	{
		$this->authorize('update', $publication);

		(new TogglePublicationBlockLinkAction)->execute($link);

		return response()->json(null, 204);
	}

	public function reorder(ReorderPublicationBlockLinkRequest $request, Publication $publication, PublicationBlock $block)
	{
		$this->authorize('update', $publication);

		(new ReorderPublicationBlockLinkAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
