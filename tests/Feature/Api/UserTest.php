<?php

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
