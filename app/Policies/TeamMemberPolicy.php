<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TeamMember;

class TeamMemberPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	public function view(User $user, TeamMember $model): bool
	{
		return true;
	}

	public function create(User $user): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function update(User $user, TeamMember $model): bool
	{
		return $user->isAdmin() || $user->isEditor();
	}

	public function delete(User $user, TeamMember $model): bool
	{
		return $user->isAdmin();
	}

	public function restore(User $user, TeamMember $model): bool
	{
		return $user->isAdmin();
	}

	public function forceDelete(User $user, TeamMember $model): bool
	{
		return $user->isAdmin();
	}
}
