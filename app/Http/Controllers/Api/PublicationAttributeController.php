<?php

namespace App\Http\Controllers\Api;

use App\Actions\PublicationAttribute\DeleteAction as DeletePublicationAttributeAction;
use App\Actions\PublicationAttribute\ReorderAction as ReorderPublicationAttributeAction;
use App\Actions\PublicationAttribute\StoreAction as StorePublicationAttributeAction;
use App\Actions\PublicationAttribute\UpdateAction as UpdatePublicationAttributeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PublicationAttribute\ReorderPublicationAttributeRequest;
use App\Http\Requests\PublicationAttribute\StorePublicationAttributeRequest;
use App\Http\Requests\PublicationAttribute\UpdatePublicationAttributeRequest;
use App\Http\Resources\PublicationAttributeResource;
use App\Models\Publication;
use App\Models\PublicationAttribute;

class PublicationAttributeController extends Controller
{
	public function index(Publication $publication)
	{
		$this->authorize('view', $publication);

		return PublicationAttributeResource::collection(
			$publication->attributes()->orderBy('sort_order')->get()
		);
	}

	public function store(StorePublicationAttributeRequest $request, Publication $publication)
	{
		$this->authorize('update', $publication);

		$attribute = (new StorePublicationAttributeAction)->execute($publication, $request->validated());

		return new PublicationAttributeResource($attribute);
	}

	public function update(UpdatePublicationAttributeRequest $request, Publication $publication, PublicationAttribute $attribute)
	{
		$this->authorize('update', $publication);

		$attribute = (new UpdatePublicationAttributeAction)->execute($attribute, $request->validated());

		return new PublicationAttributeResource($attribute);
	}

	public function destroy(Publication $publication, PublicationAttribute $attribute)
	{
		$this->authorize('update', $publication);

		(new DeletePublicationAttributeAction)->execute($attribute);

		return response()->json(null, 204);
	}

	public function reorder(ReorderPublicationAttributeRequest $request, Publication $publication)
	{
		$this->authorize('update', $publication);

		(new ReorderPublicationAttributeAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
