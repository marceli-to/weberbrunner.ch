<?php

namespace App\Console\Commands\Seed;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\File;

class Seed extends Command
{
	use ConfirmableTrait;

	protected $signature = 'app:seed {--dummy : Seed with dummy project data instead of project-data.json} {--force : Force the operation to run in production}';

	protected $description = 'Nuke all tables, run migrations, create default user and seed projects';

	public function handle(): void
	{
		if (!$this->confirmToProceed()) {
			return;
		}

		$this->info('Clearing uploads...');
		$uploadsPath = storage_path('app/public/uploads');
		if (File::isDirectory($uploadsPath)) {
			File::cleanDirectory($uploadsPath);
		}

		$this->info('Running fresh migrations...');
		$this->call('migrate:fresh', ['--force' => true]);

		$this->info('Seeding users...');
		$this->call('app:seed-users', ['--force' => true]);

		$this->info('Seeding locations...');
		$this->call('app:seed-locations', ['--force' => true]);

		$this->info('Seeding categories...');
		$this->call('app:seed-categories', ['--force' => true]);

		$this->info('Seeding statuses...');
		$this->call('app:seed-statuses', ['--force' => true]);

		$this->info('Seeding projects...');
		$this->call('app:seed-projects', ['--dummy' => $this->option('dummy'), '--force' => true]);

		$this->info('Seeding team members...');
		$this->call('app:seed-team', ['--force' => true]);

		$this->info('Seeding jobs...');
		$this->call('app:seed-jobs', ['--force' => true]);

		$this->info('Seeding office data...');
		$this->call('app:seed-office-data', ['--force' => true]);

		$this->info('Seeding masterdata...');
		$this->call('app:seed-masterdata', ['--force' => true]);

		$this->info('Done!');
	}
}
