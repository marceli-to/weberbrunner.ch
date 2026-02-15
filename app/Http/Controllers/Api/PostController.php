<?php

namespace App\Http\Controllers\Api;

use App\Actions\Post\DeleteAction as DeletePostAction;
use App\Actions\Post\ReorderAction as ReorderPostAction;
use App\Actions\Post\StoreAction as StorePostAction;
use App\Actions\Post\UpdateAction as UpdatePostAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\ReorderPostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Post::class);

		$posts = Post::orderBy('sort_order')->get();

		return PostResource::collection($posts);
	}

	public function reorder(ReorderPostRequest $request)
	{
		$this->authorize('reorder', Post::class);

		(new ReorderPostAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}

	public function store(StorePostRequest $request)
	{
		$this->authorize('create', Post::class);

		$post = (new StorePostAction)->execute($request->validated());

		return new PostResource($post);
	}

	public function show(Post $post)
	{
		$this->authorize('view', $post);

		$post->load('media');

		return new PostResource($post);
	}

	public function update(UpdatePostRequest $request, Post $post)
	{
		$this->authorize('update', $post);

		$post = (new UpdatePostAction)->execute($post, $request->validated());

		return new PostResource($post);
	}

	public function destroy(Post $post)
	{
		$this->authorize('delete', $post);

		(new DeletePostAction)->execute($post);

		return response()->json(null, 204);
	}
}
