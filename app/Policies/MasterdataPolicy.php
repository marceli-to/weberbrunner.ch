<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Masterdata;

class MasterdataPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	public function view(User $user, Masterdata $model): bool
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

	public function update(User $user, Masterdata $model): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function delete(User $user, Masterdata $model): bool
	{
		return $user->isAdmin();
	}

	public function restore(User $user, Masterdata $model): bool
	{
		return $user->isAdmin();
	}

	public function forceDelete(User $user, Masterdata $model): bool
	{
		return $user->isAdmin();
	}
}
