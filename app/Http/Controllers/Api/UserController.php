<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	public function index()
	{
		$users = User::orderBy('name')->get();

		return UserResource::collection($users);
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'firstname' => 'nullable|string|max:255',
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|string|min:8',
			'role' => 'required|in:admin,editor,viewer',
		]);

		$data['password'] = Hash::make($data['password']);

		$user = User::create($data);

		return new UserResource($user);
	}

	public function show(User $user)
	{
		return new UserResource($user);
	}

	public function update(Request $request, User $user)
	{
		$data = $request->validate([
			'firstname' => 'nullable|string|max:255',
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email,' . $user->id,
			'password' => 'nullable|string|min:8',
			'role' => 'required|in:admin,editor,viewer',
		]);

		if (!empty($data['password'])) {
			$data['password'] = Hash::make($data['password']);
		} else {
			unset($data['password']);
		}

		$user->update($data);

		return new UserResource($user);
	}

	public function destroy(User $user)
	{
		$user->delete();

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$user = User::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$user->restore();

		return new UserResource($user);
	}
}
