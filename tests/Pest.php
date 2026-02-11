<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(Tests\TestCase::class)
	->use(RefreshDatabase::class)
	->in('Feature', 'Unit');

function asAdmin(): User
{
	$user = User::factory()->create(['role' => 'admin']);
	test()->actingAs($user);

	return $user;
}

function asEditor(): User
{
	$user = User::factory()->create(['role' => 'editor']);
	test()->actingAs($user);

	return $user;
}

function asViewer(): User
{
	$user = User::factory()->create(['role' => 'viewer']);
	test()->actingAs($user);

	return $user;
}
