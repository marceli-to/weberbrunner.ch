<?php

use App\Models\Award;
use App\Models\User;

it('allows any role to viewAny awards', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', Award::class))->toBeTrue();
	expect($editor->can('viewAny', Award::class))->toBeTrue();
	expect($viewer->can('viewAny', Award::class))->toBeTrue();
});

it('allows any role to view an award', function () {
	$award = Award::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $award))->toBeTrue();
	expect($editor->can('view', $award))->toBeTrue();
	expect($viewer->can('view', $award))->toBeTrue();
});

it('allows admin and editor to create awards', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', Award::class))->toBeTrue();
	expect($editor->can('create', Award::class))->toBeTrue();
});

it('forbids viewer from creating awards', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', Award::class))->toBeFalse();
});

it('allows admin and editor to update an award', function () {
	$award = Award::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('update', $award))->toBeTrue();
	expect($editor->can('update', $award))->toBeTrue();
});

it('forbids viewer from updating an award', function () {
	$award = Award::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('update', $award))->toBeFalse();
});

it('allows only admin to delete an award', function () {
	$award = Award::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $award))->toBeTrue();
	expect($editor->can('delete', $award))->toBeFalse();
	expect($viewer->can('delete', $award))->toBeFalse();
});

it('allows only admin to restore an award', function () {
	$award = Award::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $award))->toBeTrue();
	expect($editor->can('restore', $award))->toBeFalse();
	expect($viewer->can('restore', $award))->toBeFalse();
});
