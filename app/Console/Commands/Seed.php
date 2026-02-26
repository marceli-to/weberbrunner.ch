<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class Seed extends Command
{
	protected $signature = 'app:seed {--dummy : Seed with dummy project data instead of project-data.json}';

	protected $description = 'Nuke all tables, run migrations, create default user and seed projects';

	public function handle(): void
	{
		$this->info('Clearing uploads...');
		$uploadsPath = storage_path('app/public/uploads');
		if (File::isDirectory($uploadsPath)) {
			File::cleanDirectory($uploadsPath);
		}

		$this->info('Running fresh migrations...');
		$this->call('migrate:fresh');

		$this->info('Creating default user...');
		$user = User::create([
			'name' => 'Marcel Stadelmann',
			'email' => 'm@marceli.to',
			'password' => Hash::make('7aq31rr23'),
		]);
		$user->role = 'admin';
		$user->save();

		$user2 = User::create([
			'name' => 'Benedikt Flüeler',
			'email' => 'bf@wbg.ch',
			'password' => Hash::make('wbpz-@Dm1-2026'),
		]);
		$user2->role = 'admin';
		$user2->save();

		$user3 = User::create([
			'name' => 'Bettina Puorger',
			'email' => 'bp@wbg.ch',
			'password' => Hash::make('wbpz-@Dm1-2026'),
		]);
		$user3->role = 'admin';
		$user3->save();    

		$this->info('Seeding locations...');
		$this->call('app:seed-locations');

		$this->info('Seeding categories...');
		$this->call('app:seed-categories');

		$this->info('Seeding statuses...');
		$this->call('app:seed-statuses');

		$this->info('Seeding projects...');
		$this->call('app:seed-projects', ['--dummy' => $this->option('dummy')]);

		$this->info('Seeding team members...');
		$this->call('app:seed-team');

		$this->info('Seeding jobs...');
		$this->call('app:seed-jobs');

		$this->info('Seeding office data...');
		$this->call('app:seed-office-data');

		$this->info('Done!');
	}
}
