<?php

namespace App\Http\Controllers\Api;

use App\Actions\User\DeleteAction as DeleteUserAction;
use App\Actions\User\StoreAction as StoreUserAction;
use App\Actions\User\UpdateAction as UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', User::class);

		$users = User::orderBy('name')->get();

		return UserResource::collection($users);
	}

	public function store(StoreUserRequest $request)
	{
		$this->authorize('create', User::class);

		$user = (new StoreUserAction)->execute($request->validated());

		return new UserResource($user);
	}

	public function show(User $user)
	{
		$this->authorize('view', $user);

		return new UserResource($user);
	}

	public function update(UpdateUserRequest $request, User $user)
	{
		$this->authorize('update', $user);

		$user = (new UpdateUserAction)->execute($user, $request->validated());

		return new UserResource($user);
	}

	public function destroy(User $user)
	{
		$this->authorize('delete', $user);

		(new DeleteUserAction)->execute($user);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$user = User::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $user);

		$user->restore();

		return new UserResource($user);
	}
}
