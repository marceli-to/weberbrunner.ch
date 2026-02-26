<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LandingItem;

class LandingItemPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	public function view(User $user, LandingItem $model): bool
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

	public function update(User $user, LandingItem $model): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function delete(User $user, LandingItem $model): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}
}
