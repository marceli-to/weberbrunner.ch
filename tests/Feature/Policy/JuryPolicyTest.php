<?php

use App\Models\Jury;
use App\Models\User;

it('allows any role to viewAny juries', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', Jury::class))->toBeTrue();
	expect($editor->can('viewAny', Jury::class))->toBeTrue();
	expect($viewer->can('viewAny', Jury::class))->toBeTrue();
});

it('allows any role to view a jury', function () {
	$jury = Jury::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $jury))->toBeTrue();
	expect($editor->can('view', $jury))->toBeTrue();
	expect($viewer->can('view', $jury))->toBeTrue();
});

it('allows admin and editor to create juries', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', Jury::class))->toBeTrue();
	expect($editor->can('create', Jury::class))->toBeTrue();
});

it('forbids viewer from creating juries', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', Jury::class))->toBeFalse();
});

it('allows admin and editor to update a jury', function () {
	$jury = Jury::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('update', $jury))->toBeTrue();
	expect($editor->can('update', $jury))->toBeTrue();
});

it('forbids viewer from updating a jury', function () {
	$jury = Jury::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('update', $jury))->toBeFalse();
});

it('allows only admin to delete a jury', function () {
	$jury = Jury::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $jury))->toBeTrue();
	expect($editor->can('delete', $jury))->toBeFalse();
	expect($viewer->can('delete', $jury))->toBeFalse();
});

it('allows only admin to restore a jury', function () {
	$jury = Jury::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $jury))->toBeTrue();
	expect($editor->can('restore', $jury))->toBeFalse();
	expect($viewer->can('restore', $jury))->toBeFalse();
});
