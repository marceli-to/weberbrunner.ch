<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StoreAction
{
	use ResolvesUserAttributes;

	public function execute(array $data): User
	{
		$attributes = $this->resolveAttributes($data);
		$attributes['password'] = Hash::make($data['password']);

		$user = User::create($attributes);
		$user->role = $data['role'];
		$user->save();

		return $user;
	}
}
