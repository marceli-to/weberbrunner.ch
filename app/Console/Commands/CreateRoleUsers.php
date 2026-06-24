<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateRoleUsers extends Command
{
	protected $signature = 'app:create-role-users';

	protected $description = 'Create one user per role (admin/editor/viewer) for testing';

	public function handle(): int
	{
		$users = [
			['email' => 'publizierende@weberbrunner.ch', 'firstname' => 'Publizierende', 'name' => 'Person', 'role' => 'admin'],
			['email' => 'autorinnen@weberbrunner.ch', 'firstname' => 'Autorinnen', 'name' => 'Person', 'role' => 'editor'],
			['email' => 'lesende@weberbrunner.ch', 'firstname' => 'Lesende', 'name' => 'Person', 'role' => 'viewer'],
		];

		foreach ($users as $data) {
			$user = User::updateOrCreate(
				['email' => $data['email']],
				[
					'firstname' => $data['firstname'],
					'name' => $data['name'],
					'password' => 'weberbrunner2026',
				],
			);

			$user->role = $data['role'];
			$user->save();

			$this->info("{$user->email} ({$data['role']})");
		}

		return self::SUCCESS;
	}
}
