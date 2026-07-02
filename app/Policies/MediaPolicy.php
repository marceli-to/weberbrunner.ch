<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Media;

class MediaPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	public function view(User $user, Media $model): bool
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

	public function update(User $user, Media $model): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function publish(User $user, Media $model): bool
	{
		return $user->isAdmin();
	}

	public function delete(User $user, Media $model): bool
	{
		return $user->isAdmin();
	}

	public function restore(User $user, Media $model): bool
	{
		return $user->isAdmin();
	}

	public function forceDelete(User $user, Media $model): bool
	{
		return $user->isAdmin();
	}
}
