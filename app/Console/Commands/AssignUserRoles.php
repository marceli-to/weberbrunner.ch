<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignUserRoles extends Command
{
	protected $signature = 'app:assign-user-roles
		{--force : Actually update rows (default is dry-run)}';

	protected $description = 'Assign roles to users from the DataHub Mitarbeitende list (admin/editor explicit, everyone else viewer)';

	private const ADMINS = [
		'laurent.baumgartner@weberbrunner.ch',
		'elise.pischetsrieder@wbp-architektur.de',
		'roger.weber@weberbrunner.ch',
		'sophie.ziemer@wbp-architektur.de',
	];

	private const EDITORS = [
		'beatrice.borggreve@weberbrunner.ch',
		'boris.brunner@weberbrunner.ch',
		'eva.geering@weberbrunner.ch',
		'silke.geuer@weberbrunner.ch',
		'nicole.hangartner@weberbrunner.ch',
		'volker.schopp@weberbrunner.ch',
	];

	public function handle(): int
	{
		$isDryRun = ! $this->option('force');

		$roleByEmail = [];
		foreach (self::ADMINS as $email) {
			$roleByEmail[strtolower($email)] = 'admin';
		}
		foreach (self::EDITORS as $email) {
			$roleByEmail[strtolower($email)] = 'editor';
		}

		$users = User::orderBy('name')->get();

		$changed = [];
		$unchanged = 0;

		foreach ($users as $user) {
			$target = $roleByEmail[strtolower($user->email)] ?? 'viewer';

			if ($user->role === $target) {
				$unchanged++;
				continue;
			}

			$changed[] = [
				'user' => $user,
				'from' => $user->role,
				'to' => $target,
			];
		}

		$missingAdmins = $this->missingEmails(self::ADMINS, $users);
		$missingEditors = $this->missingEmails(self::EDITORS, $users);

		foreach ($missingAdmins as $email) {
			$this->warn("No user found for admin: {$email}");
		}
		foreach ($missingEditors as $email) {
			$this->warn("No user found for editor: {$email}");
		}

		$this->line('');
		$this->line(($isDryRun ? '[dry-run] ' : '') . 'Role changes:');

		if (empty($changed)) {
			$this->info('Nothing to change — all users already have the expected role.');
			return self::SUCCESS;
		}

		foreach ($changed as $row) {
			$from = $row['from'] ?? '(none)';
			$this->line(($isDryRun ? '[dry-run] ' : '') . "{$row['user']->email}: {$from} → {$row['to']}");
		}

		if (! $isDryRun) {
			foreach ($changed as $row) {
				$row['user']->role = $row['to'];
				$row['user']->save();
			}
		}

		$this->line('');
		$this->info($isDryRun
			? count($changed) . " user(s) would be updated, {$unchanged} already correct. Run with --force to apply."
			: count($changed) . " user(s) updated, {$unchanged} already correct."
		);

		return self::SUCCESS;
	}

	private function missingEmails(array $emails, $users): array
	{
		$present = $users->map(fn ($u) => strtolower($u->email))->all();

		return array_values(array_filter(
			$emails,
			fn ($email) => ! in_array(strtolower($email), $present, true)
		));
	}
}
