<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MasterdataGroup;

class MasterdataGroupPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	public function view(User $user, MasterdataGroup $model): bool
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

	public function update(User $user, MasterdataGroup $model): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function delete(User $user, MasterdataGroup $model): bool
	{
		return $user->isAdmin();
	}

	public function restore(User $user, MasterdataGroup $model): bool
	{
		return $user->isAdmin();
	}

	public function forceDelete(User $user, MasterdataGroup $model): bool
	{
		return $user->isAdmin();
	}
}
