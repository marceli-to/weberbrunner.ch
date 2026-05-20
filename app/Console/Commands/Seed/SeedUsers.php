<?php

namespace App\Console\Commands\Seed;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Hash;

class SeedUsers extends Command
{
	use ConfirmableTrait;

	protected $signature = 'app:seed-users {--force : Force the operation to run in production}';

	protected $description = 'Seed default users';

	private array $users = [
		[
			'name' => 'Marcel Stadelmann',
			'email' => 'm@marceli.to',
			'password' => '7aq31rr23',
			'role' => 'admin',
		],
		[
			'name' => 'Benedikt Flüeler',
			'email' => 'bf@wbg.ch',
			'password' => 'wbpz-@Dm1-2026',
			'role' => 'admin',
		],
		[
			'name' => 'Bettina Puorger',
			'email' => 'bp@wbg.ch',
			'password' => 'wbpz-@Dm1-2026',
			'role' => 'admin',
		],
		[
			'name' => 'Elise Pischetsrieder',
			'email' => 'elise.pischetsrieder@weberbrunner.de',
			'password' => 'wbpz-@Dm1-2026',
			'role' => 'admin',
		],
		[
			'name' => 'Roger Weber',
			'email' => 'roger.weber@weberbrunner.ch',
			'password' => 'wbpz-@Dm1-2026',
			'role' => 'admin',
		],
		[
			'name' => 'Boris Brunner',
			'email' => 'boris.brunner@weberbrunner.ch',
			'password' => 'wbpz-@Dm1-2026',
			'role' => 'admin',
		],
	];

	public function handle(): void
	{
		if (!$this->confirmToProceed()) {
			return;
		}

		foreach ($this->users as $data) {
			$user = User::firstOrCreate(
				['email' => $data['email']],
				[
					'name' => $data['name'],
					'password' => Hash::make($data['password']),
				]
			);
			$user->role = $data['role'];
			$user->save();
			$this->line("  {$data['name']}");
		}

		$this->info('Done! Seeded ' . count($this->users) . ' users.');
	}
}
