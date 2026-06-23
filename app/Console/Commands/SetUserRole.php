<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class SetUserRole extends Command
{
	protected $signature = 'app:set-user-role
		{email? : The email of the user to update}
		{role? : The role to assign (admin/editor/viewer)}';

	protected $description = 'Set a single user\'s role interactively (for testing)';

	public function handle(): int
	{
		$email = $this->argument('email') ?? text(
			label: 'User email',
			required: true,
		);

		$user = User::where('email', $email)->first();

		if (! $user) {
			$this->error("No user found for: {$email}");
			return self::FAILURE;
		}

		$role = $this->argument('role') ?? select(
			label: "Role for {$user->firstname} {$user->name} (currently: {$user->role})",
			options: ['admin', 'editor', 'viewer'],
			default: $user->role ?? 'viewer',
		);

		if (! in_array($role, ['admin', 'editor', 'viewer'], true)) {
			$this->error("Invalid role: {$role}");
			return self::FAILURE;
		}

		$from = $user->role ?? '(none)';
		$user->role = $role;
		$user->save();

		$this->info("{$user->email}: {$from} → {$role}");

		return self::SUCCESS;
	}
}
