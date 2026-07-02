<?php

namespace App\Http\Controllers\Api;

use App\Actions\Media\DeleteAction as DeleteMediaAction;
use App\Actions\Media\ListAction as ListMediaAction;
use App\Actions\Media\PersistAction as PersistMediaAction;
use App\Actions\Media\ReorderAction as ReorderMediaAction;
use App\Actions\Media\SetOgAction;
use App\Actions\Media\SetTeaserAction;
use App\Actions\Media\TogglePublishAction;
use App\Actions\Media\UpdateAction as UpdateMediaAction;
use App\Actions\Media\UploadAction as UploadMediaAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\ReorderMediaRequest;
use App\Http\Requests\Media\UpdateMediaRequest;
use App\Http\Requests\Media\UploadMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
	public function index(Request $request)
	{
		$this->authorize('viewAny', Media::class);

		$media = (new ListMediaAction)->execute($request->query('search'));

		return MediaResource::collection($media);
	}

	public function upload(UploadMediaRequest $request)
	{
		$this->authorize('create', Media::class);

		$data = (new UploadMediaAction)->execute($request->file('file'));

		return response()->json(['data' => $data]);
	}

	public function persist(Request $request)
	{
		$this->authorize('create', Media::class);

		$media = (new PersistMediaAction)->execute($request->all());

		return new MediaResource($media);
	}

	public function update(UpdateMediaRequest $request, Media $media)
	{
		$this->authorize('update', $media);

		$media = (new UpdateMediaAction)->execute($media, $request->validated());

		return new MediaResource($media);
	}

	public function destroy(Media $media)
	{
		$this->authorize('delete', $media);

		(new DeleteMediaAction)->execute($media);

		return response()->json(null, 204);
	}

	public function reorder(ReorderMediaRequest $request)
	{
		$this->authorize('reorder', Media::class);

		(new ReorderMediaAction)->execute($request->validated('items'));

		return response()->json(['message' => 'ok']);
	}

	public function teaser(Media $media)
	{
		$this->authorize('update', $media);

		$media = (new SetTeaserAction)->execute($media);

		return new MediaResource($media);
	}

	public function og(Media $media)
	{
		$this->authorize('update', $media);

		$media = (new SetOgAction)->execute($media);

		return new MediaResource($media);
	}

	public function togglePublish(Media $media)
	{
		$this->authorize('publish', $media);

		$media = (new TogglePublishAction)->execute($media);

		return new MediaResource($media);
	}
}
