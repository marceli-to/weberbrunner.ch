<?php

namespace App\Http\Controllers\Api;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\AttachMediaRequest;
use App\Http\Resources\PublicationResource;
use App\Models\Publication;

class PublicationMediaController extends Controller
{
	public function attach(AttachMediaRequest $request, Publication $publication)
	{
		$this->authorize('update', $publication);

		(new AttachMediaAction)->execute($request->validated('media'), $publication);

		return new PublicationResource($publication->load(['location', 'attributes', 'blocks.media', 'media', 'teaser', 'og']));
	}
}
