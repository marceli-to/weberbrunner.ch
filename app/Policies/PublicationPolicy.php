<?php

namespace App\Policies;

use App\Models\Publication;
use App\Models\User;

class PublicationPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	public function view(User $user, Publication $model): bool
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

	public function update(User $user, Publication $model): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function delete(User $user, Publication $model): bool
	{
		return $user->isAdmin();
	}

	public function restore(User $user, Publication $model): bool
	{
		return $user->isAdmin();
	}

	public function forceDelete(User $user, Publication $model): bool
	{
		return $user->isAdmin();
	}
}
