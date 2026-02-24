<?php

namespace App\Http\Controllers\Api;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Actions\Contact\DeleteAction as DeleteContactAction;
use App\Actions\Contact\ListAction as ListContactAction;
use App\Actions\Contact\ReorderAction as ReorderContactAction;
use App\Actions\Contact\StoreAction as StoreContactAction;
use App\Actions\Contact\ToggleAction as ToggleContactAction;
use App\Actions\Contact\UpdateAction as UpdateContactAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\AttachMediaRequest;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\ReorderContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Http\Resources\LocationResource;
use App\Models\Contact;

class ContactController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Contact::class);

		$locations = (new ListContactAction)->execute();

		$grouped = $locations->map(fn ($location) => [
			'location' => new LocationResource($location),
			'contacts' => ContactResource::collection($location->contacts),
		]);

		return response()->json(['data' => $grouped]);
	}

	public function store(StoreContactRequest $request)
	{
		$this->authorize('create', Contact::class);

		$contact = (new StoreContactAction)->execute($request->validated());

		return new ContactResource($contact->load(['location', 'media']));
	}

	public function show(Contact $contact)
	{
		$this->authorize('view', $contact);

		$contact->load(['location', 'media']);

		return new ContactResource($contact);
	}

	public function update(UpdateContactRequest $request, Contact $contact)
	{
		$this->authorize('update', $contact);

		$contact = (new UpdateContactAction)->execute($contact, $request->validated());

		return new ContactResource($contact->load(['location', 'media']));
	}

	public function toggle(Contact $contact)
	{
		$this->authorize('update', $contact);

		(new ToggleContactAction)->execute($contact);

		return response()->json(null, 204);
	}

	public function destroy(Contact $contact)
	{
		$this->authorize('delete', $contact);

		(new DeleteContactAction)->execute($contact);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$contact = Contact::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $contact);

		$contact->restore();

		return new ContactResource($contact->load(['location', 'media']));
	}

	public function attachMedia(AttachMediaRequest $request, Contact $contact)
	{
		(new AttachMediaAction)->execute($request->validated('media'), $contact);

		return new ContactResource($contact->load(['location', 'media']));
	}

	public function reorder(ReorderContactRequest $request)
	{
		$this->authorize('reorder', Contact::class);

		(new ReorderContactAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
