<?php

use App\Models\TeamMember;
use App\Models\User;

function writeCsv(array $rows): string
{
	$path = tempnam(sys_get_temp_dir(), 'dh') . '.csv';
	$fh = fopen($path, 'w');
	fputcsv($fh, ['name', 'vorname', 'email', 'standort', 'berechtigung']);
	foreach ($rows as $r) {
		fputcsv($fh, $r);
	}
	fclose($fh);

	return $path;
}

function outPath(): string
{
	return tempnam(sys_get_temp_dir(), 'cred') . '.csv';
}

$passwordPattern = '/^[A-HJ-NP-Z2-9]{4}-[A-HJ-NP-Z2-9]{4}-[A-HJ-NP-Z2-9]{4}$/';

it('creates an intern user linked to a matching team member using the team member email', function () use ($passwordPattern) {
	$member = TeamMember::factory()->create([
		'firstname' => 'Roger', 'name' => 'Weber', 'email' => 'roger.weber@tm.example',
	]);
	$csv = writeCsv([['Weber', 'Roger', 'roger.weber@sheet.example', 'Zürich', 'A']]);
	$out = outPath();

	$this->artisan('app:import-datahub-users', ['--file' => $csv, '--output' => $out, '--force' => true])
		->assertSuccessful();

	$user = User::where('name', 'Weber')->first();
	expect($user)->not->toBeNull();
	expect($user->email)->toBe('roger.weber@tm.example');
	expect($user->team_member_id)->toBe($member->id);
	expect($user->role)->toBe('admin');

	$creds = array_map('str_getcsv', file($out));
	expect($creds[1][1])->toBe('roger.weber@tm.example');
	expect($creds[1][2])->toMatch($passwordPattern);
});

it('creates an extern user with the sheet email when no team member matches', function () {
	$csv = writeCsv([['Extern', 'Ella', 'ella.extern@sheet.example', 'Zürich', 'C']]);

	$this->artisan('app:import-datahub-users', ['--file' => $csv, '--output' => outPath(), '--force' => true])
		->assertSuccessful();

	$user = User::where('name', 'Extern')->first();
	expect($user->email)->toBe('ella.extern@sheet.example');
	expect($user->team_member_id)->toBeNull();
	expect($user->role)->toBe('viewer');
});

it('converts an existing full-name external user to intern and keeps the password', function () {
	$existing = User::factory()->create([
		'firstname' => null,
		'name' => 'Elise Pischetsrieder',
		'email' => 'elise@old.example',
	]);
	$existing->role = 'admin';
	$existing->save();
	$originalPassword = $existing->password;

	$member = TeamMember::factory()->create([
		'firstname' => 'Elise', 'name' => 'Pischetsrieder', 'email' => 'elise@tm.example',
	]);

	$csv = writeCsv([['Pischetsrieder', 'Elise', 'elise.p@sheet.example', 'Berlin', 'A']]);

	$this->artisan('app:import-datahub-users', ['--file' => $csv, '--output' => outPath(), '--force' => true])
		->assertSuccessful();

	$existing->refresh();
	expect(User::where('name', 'Pischetsrieder')->count())->toBe(1);
	expect($existing->team_member_id)->toBe($member->id);
	expect($existing->email)->toBe('elise@tm.example');
	expect($existing->password)->toBe($originalPassword);
});

it('is idempotent and never touches an existing password on re-run', function () {
	$existing = User::factory()->create(['firstname' => null, 'name' => 'Roger Weber', 'email' => 'roger.weber@tm.example']);
	$original = $existing->password;
	TeamMember::factory()->create(['firstname' => 'Roger', 'name' => 'Weber', 'email' => 'roger.weber@tm.example']);
	$csv = writeCsv([
		['Weber', 'Roger', 'roger.weber@sheet.example', 'Zürich', 'A'],
		['Extern', 'Ella', 'ella@sheet.example', 'Zürich', 'C'],
	]);

	$this->artisan('app:import-datahub-users', ['--file' => $csv, '--output' => outPath(), '--force' => true]);
	$countAfterFirst = User::count();

	$this->artisan('app:import-datahub-users', ['--file' => $csv, '--output' => outPath(), '--force' => true]);

	expect(User::count())->toBe($countAfterFirst);
	expect($existing->fresh()->password)->toBe($original);
});

it('writes nothing in dry-run mode', function () {
	TeamMember::factory()->create(['firstname' => 'Roger', 'name' => 'Weber', 'email' => 'roger.weber@tm.example']);
	$csv = writeCsv([['Weber', 'Roger', 'roger.weber@sheet.example', 'Zürich', 'A']]);

	$this->artisan('app:import-datahub-users', ['--file' => $csv, '--dry-run' => true])
		->assertSuccessful();

	expect(User::where('name', 'Weber')->exists())->toBeFalse();
});
