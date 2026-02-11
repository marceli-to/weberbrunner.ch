<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Award;

class AwardPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	public function view(User $user, Award $model): bool
	{
		return true;
	}

	public function create(User $user): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function update(User $user, Award $model): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function delete(User $user, Award $model): bool
	{
		return $user->isAdmin();
	}

	public function restore(User $user, Award $model): bool
	{
		return $user->isAdmin();
	}

	public function forceDelete(User $user, Award $model): bool
	{
		return $user->isAdmin();
	}
}
