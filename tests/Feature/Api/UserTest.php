<?php

use App\Models\TeamMember;
use App\Models\User;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/users')->assertUnauthorized();
});

it('lists users', function () {
	asAdmin();
	User::factory()->count(2)->create();
	$this->getJson('/api/dashboard/users')
		->assertOk();
});

it('creates a user', function () {
	asAdmin();
	$this->postJson('/api/dashboard/users', [
		'name' => 'Doe',
		'email' => 'doe@example.com',
		'password' => 'password123',
		'role' => 'editor',
	])
		->assertCreated()
		->assertJsonPath('data.name', 'Doe');
});

it('validates required fields on store', function () {
	asAdmin();
	$this->postJson('/api/dashboard/users', [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['name', 'email', 'password', 'role']);
});

it('validates unique email', function () {
	asAdmin();
	User::factory()->create(['email' => 'taken@example.com']);
	$this->postJson('/api/dashboard/users', [
		'name' => 'Doe',
		'email' => 'taken@example.com',
		'password' => 'password123',
		'role' => 'viewer',
	])
		->assertUnprocessable()
		->assertJsonValidationErrors('email');
});

it('creates an extern user with explicit type', function () {
	asAdmin();
	$this->postJson('/api/dashboard/users', [
		'type' => 'extern',
		'firstname' => 'Ext',
		'name' => 'Erna',
		'email' => 'ext@example.com',
		'password' => 'password123',
		'role' => 'editor',
	])
		->assertCreated()
		->assertJsonPath('data.type', 'extern')
		->assertJsonPath('data.team_member_id', null);
});

it('creates an intern user linked to a team member', function () {
	asAdmin();
	$member = TeamMember::factory()->create([
		'firstname' => 'Tina',
		'name' => 'Team',
		'email' => 'tina@example.com',
	]);

	$this->postJson('/api/dashboard/users', [
		'type' => 'intern',
		'team_member_id' => $member->id,
		'password' => 'password123',
		'role' => 'editor',
	])
		->assertCreated()
		->assertJsonPath('data.type', 'intern')
		->assertJsonPath('data.team_member_id', $member->id)
		->assertJsonPath('data.name', 'Team')
		->assertJsonPath('data.email', 'tina@example.com');
});

it('rejects an intern user whose team member has no email', function () {
	asAdmin();
	$member = TeamMember::factory()->create(['email' => null]);

	$this->postJson('/api/dashboard/users', [
		'type' => 'intern',
		'team_member_id' => $member->id,
		'password' => 'password123',
		'role' => 'viewer',
	])
		->assertUnprocessable()
		->assertJsonValidationErrors('team_member_id');
});

it('rejects linking a team member that already has a user', function () {
	asAdmin();
	$member = TeamMember::factory()->create(['email' => 'linked@example.com']);
	User::factory()->create(['team_member_id' => $member->id]);

	$this->postJson('/api/dashboard/users', [
		'type' => 'intern',
		'team_member_id' => $member->id,
		'password' => 'password123',
		'role' => 'viewer',
	])
		->assertUnprocessable()
		->assertJsonValidationErrors('team_member_id');
});

it('shows a user', function () {
	asAdmin();
	$user = User::factory()->create();
	$this->getJson("/api/dashboard/users/{$user->uuid}")
		->assertOk();
});

it('updates a user', function () {
	asAdmin();
	$user = User::factory()->create();
	$this->putJson("/api/dashboard/users/{$user->uuid}", [
		'name' => 'Updated',
		'email' => $user->email,
		'role' => 'admin',
	])
		->assertOk()
		->assertJsonPath('data.name', 'Updated');
});

it('updates a user without changing password', function () {
	asAdmin();
	$user = User::factory()->create();
	$originalPassword = $user->password;
	$this->putJson("/api/dashboard/users/{$user->uuid}", [
		'name' => 'Same',
		'email' => $user->email,
		'role' => 'viewer',
		'password' => '',
	]);
	expect($user->fresh()->password)->toBe($originalPassword);
});

it('deletes a user', function () {
	asAdmin();
	$user = User::factory()->create();
	$this->deleteJson("/api/dashboard/users/{$user->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted user', function () {
	asAdmin();
	$user = User::factory()->create();
	$user->delete();
	$this->patchJson("/api/dashboard/users/{$user->uuid}/restore")
		->assertOk();
	expect(User::withTrashed()->where('id', $user->id)->first()->deleted_at)->toBeNull();
});
