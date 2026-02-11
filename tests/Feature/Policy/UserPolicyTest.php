<?php

use App\Models\User;

it('allows admin and editor to viewAny users', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('viewAny', User::class))->toBeTrue();
	expect($editor->can('viewAny', User::class))->toBeTrue();
});

it('forbids viewer from viewAny users', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('viewAny', User::class))->toBeFalse();
});

it('allows admin and editor to view a user', function () {
	$target = User::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('view', $target))->toBeTrue();
	expect($editor->can('view', $target))->toBeTrue();
});

it('forbids viewer from viewing a user', function () {
	$target = User::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('view', $target))->toBeFalse();
});

it('allows only admin to create users', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('create', User::class))->toBeTrue();
	expect($editor->can('create', User::class))->toBeFalse();
	expect($viewer->can('create', User::class))->toBeFalse();
});

it('allows only admin to update a user', function () {
	$target = User::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('update', $target))->toBeTrue();
	expect($editor->can('update', $target))->toBeFalse();
	expect($viewer->can('update', $target))->toBeFalse();
});

it('allows only admin to delete a user', function () {
	$target = User::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $target))->toBeTrue();
	expect($editor->can('delete', $target))->toBeFalse();
	expect($viewer->can('delete', $target))->toBeFalse();
});

it('allows only admin to restore a user', function () {
	$target = User::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $target))->toBeTrue();
	expect($editor->can('restore', $target))->toBeFalse();
	expect($viewer->can('restore', $target))->toBeFalse();
});
