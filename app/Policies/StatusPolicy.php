<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Status;

class StatusPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	public function view(User $user, Status $model): bool
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

	public function update(User $user, Status $model): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function delete(User $user, Status $model): bool
	{
		return $user->isAdmin();
	}

	public function restore(User $user, Status $model): bool
	{
		return $user->isAdmin();
	}

	public function forceDelete(User $user, Status $model): bool
	{
		return $user->isAdmin();
	}
}
