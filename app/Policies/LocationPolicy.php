<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Location;

class LocationPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	public function view(User $user, Location $model): bool
	{
		return true;
	}

	public function reorder(User $user): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function create(User $user): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function update(User $user, Location $model): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function delete(User $user, Location $model): bool
	{
		return $user->isAdmin();
	}

	public function restore(User $user, Location $model): bool
	{
		return $user->isAdmin();
	}

	public function forceDelete(User $user, Location $model): bool
	{
		return $user->isAdmin();
	}
}
