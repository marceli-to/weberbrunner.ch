<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateAction
{
	use ResolvesUserAttributes;

	public function execute(User $user, array $data): User
	{
		$attributes = $this->resolveAttributes($data);

		if (!empty($data['password'])) {
			$attributes['password'] = Hash::make($data['password']);
		}

		$user->update($attributes);
		$user->role = $data['role'];
		$user->save();

		return $user;
	}
}
