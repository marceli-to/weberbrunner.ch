<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	public function view(User $user, Project $model): bool
	{
		return true;
	}

	public function create(User $user): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function update(User $user, Project $model): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function delete(User $user, Project $model): bool
	{
		return $user->isAdmin();
	}

	public function restore(User $user, Project $model): bool
	{
		return $user->isAdmin();
	}

	public function forceDelete(User $user, Project $model): bool
	{
		return $user->isAdmin();
	}
}
