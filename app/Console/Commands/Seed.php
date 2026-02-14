<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Seed extends Command
{
	protected $signature = 'app:seed';

	protected $description = 'Nuke all tables, run migrations, create default user and seed projects';

	public function handle(): void
	{
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

		$this->info('Seeding locations...');
		$this->call('app:seed-locations');

		$this->info('Seeding projects...');
		$this->call('app:seed-projects');

		$this->info('Seeding blog posts...');
		$this->call('app:seed-posts');

		$this->info('Seeding team members...');
		$this->call('app:seed-team');

		$this->info('Seeding jobs...');
		$this->call('app:seed-jobs');

		$this->info('Seeding office data...');
		$this->call('app:seed-office-data');

		$this->info('Updating media dimensions...');
		$this->updateMediaDimensions();

		$this->info('Done!');
	}

	private function updateMediaDimensions(): void
	{
		$media = Media::all();

		foreach ($media as $item) {
			$path = Storage::disk('public')->path($item->file);

			if (file_exists($path) && $size = @getimagesize($path)) {
				$item->update([
					'width' => $size[0],
					'height' => $size[1],
				]);
			}
		}
	}
}
