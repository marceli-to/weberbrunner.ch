<?php

namespace App\Console\Commands;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Hash;

class ImportDataHubUsers extends Command
{
	use ConfirmableTrait;

	protected $signature = 'app:import-datahub-users
		{--dry-run : Preview the changes without writing anything}
		{--file= : Path to the CSV (defaults to database/data/datahub-users.csv)}
		{--output= : Where to write the credentials CSV for newly created users}
		{--force : Force the operation to run in production}';

	protected $description = 'Import DataHub users from a CSV, linking team members as internal users';

	private array $roleMap = [
		'A' => 'admin',
		'B' => 'editor',
		'C' => 'viewer',
	];

	private array $roleLabels = [
		'admin' => 'Publizierende',
		'editor' => 'Autor:innen',
		'viewer' => 'Lesende',
	];

	private const PASSWORD_ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

	public function handle(): int
	{
		$dryRun = (bool) $this->option('dry-run');

		if (!$dryRun && !$this->confirmToProceed()) {
			return self::FAILURE;
		}

		$file = $this->option('file') ?: database_path('data/datahub-users.csv');

		if (!is_readable($file)) {
			$this->error("CSV not found or not readable: {$file}");

			return self::FAILURE;
		}

		$people = $this->readCsv($file);
		$this->line(($dryRun ? '[DRY RUN] ' : '') . 'Processing ' . count($people) . " people from {$file}\n");

		$created = [];
		$linked = 0;
		$updated = 0;
		$skipped = 0;

		foreach ($people as $p) {
			$role = $this->roleMap[$p['berechtigung']] ?? null;

			if (!$role) {
				$this->warn("SKIP  {$p['fullname']} — unknown Berechtigung '{$p['berechtigung']}'");
				$skipped++;
				continue;
			}

			$member = TeamMember::where('firstname', $p['vorname'])->where('name', $p['name'])->first();
			$existing = $this->findExistingUser($p, $member);

			$intern = $member !== null;

			if ($intern && !$member->email) {
				$this->warn("EXTERN {$p['fullname']} — matched team member has no email, falling back to extern");
				$intern = false;
			}

			if ($intern) {
				$linkedElsewhere = User::where('team_member_id', $member->id)
					->when($existing, fn ($q) => $q->where('id', '!=', $existing->id))
					->exists();

				if ($linkedElsewhere) {
					$this->warn("SKIP  {$p['fullname']} — team member already linked to another user");
					$skipped++;
					continue;
				}
			}

			$target = $intern
				? [
					'firstname' => $member->firstname,
					'name' => $member->name,
					'email' => $member->email,
					'team_member_id' => $member->id,
				]
				: [
					'firstname' => $p['vorname'],
					'name' => $p['name'],
					'email' => $p['email'],
					'team_member_id' => null,
				];

			$emailClash = User::where('email', $target['email'])
				->when($existing, fn ($q) => $q->where('id', '!=', $existing->id))
				->exists();

			if ($emailClash) {
				$this->warn("SKIP  {$p['fullname']} — email {$target['email']} already used by another user");
				$skipped++;
				continue;
			}

			$kind = $intern ? 'intern' : 'extern';

			if ($existing) {
				$changes = [];
				if ($existing->email !== $target['email']) {
					$changes[] = "email {$existing->email} → {$target['email']}";
				}
				if ($existing->team_member_id !== $target['team_member_id']) {
					$changes[] = $intern ? 'link → intern' : 'unlink → extern';
				}
				if ($existing->role !== $role) {
					$changes[] = "role {$existing->role} → {$role}";
				}
				$note = $changes ? ' (' . implode(', ', $changes) . ')' : ' (no change)';
				$this->line("UPDATE [{$kind}] {$p['fullname']}{$note} — password kept");

				if (!$dryRun) {
					$existing->fill($target);
					$existing->role = $role;
					$existing->save();
				}
				$intern ? $linked++ : $updated++;
				continue;
			}

			$password = $this->generatePassword();
			$this->line("CREATE [{$kind}] {$p['fullname']} <{$target['email']}> role={$role}");

			if (!$dryRun) {
				$user = User::create($target + ['password' => Hash::make($password)]);
				$user->role = $role;
				$user->save();
			}

			$created[] = [
				'name' => $p['vorname'] . ' ' . $p['name'],
				'email' => $target['email'],
				'password' => $password,
				'role' => $role,
				'type' => $kind,
			];
		}

		$this->newLine();
		$this->info(($dryRun ? '[DRY RUN] Would create' : 'Created') . ': ' . count($created)
			. ' | linked (intern): ' . $linked
			. ' | updated (extern): ' . $updated
			. ' | skipped: ' . $skipped);

		if (!$dryRun && $created) {
			$path = $this->writeCredentials($created);
			$this->info("Credentials for new users written to: {$path}");
			$this->warn('Distribute these securely, then delete the file.');
		}

		if ($dryRun && $created) {
			$this->newLine();
			$this->comment('Passwords will be generated on the real run and written to a credentials CSV.');
		}

		return self::SUCCESS;
	}

	private function readCsv(string $file): array
	{
		$rows = array_map('str_getcsv', file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
		$header = array_map('trim', array_shift($rows));

		$people = [];
		foreach ($rows as $row) {
			$data = array_combine($header, array_map('trim', $row));
			$people[] = [
				'name' => $data['name'],
				'vorname' => $data['vorname'],
				'email' => $data['email'],
				'standort' => $data['standort'] ?? '',
				'berechtigung' => strtoupper($data['berechtigung'] ?? ''),
				'fullname' => $data['vorname'] . ' ' . $data['name'],
			];
		}

		return $people;
	}

	private function findExistingUser(array $p, ?TeamMember $member): ?User
	{
		$fullname = $p['vorname'] . ' ' . $p['name'];

		return User::where('firstname', $p['vorname'])->where('name', $p['name'])->first()
			?? User::where('name', $fullname)->first()
			?? User::where('email', $p['email'])->first()
			?? ($member ? User::where('email', $member->email)->first() : null)
			?? ($member ? User::where('team_member_id', $member->id)->first() : null);
	}

	private function generatePassword(): string
	{
		$groups = [];
		for ($g = 0; $g < 3; $g++) {
			$chunk = '';
			for ($i = 0; $i < 4; $i++) {
				$chunk .= self::PASSWORD_ALPHABET[random_int(0, strlen(self::PASSWORD_ALPHABET) - 1)];
			}
			$groups[] = $chunk;
		}

		return implode('-', $groups);
	}

	private function writeCredentials(array $created): string
	{
		$path = $this->option('output') ?: storage_path('app/datahub-credentials-' . date('Ymd-His') . '.csv');

		$handle = fopen($path, 'w');
		fputcsv($handle, ['name', 'email', 'password', 'role', 'type']);
		foreach ($created as $row) {
			$roleLabel = $this->roleLabels[$row['role']] ?? $row['role'];
			fputcsv($handle, [$row['name'], $row['email'], $row['password'], $roleLabel, $row['type']]);
		}
		fclose($handle);

		return $path;
	}
}
