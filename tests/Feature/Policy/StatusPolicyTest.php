<?php

use App\Models\Status;
use App\Models\User;

it('allows any role to viewAny statuses', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', Status::class))->toBeTrue();
	expect($editor->can('viewAny', Status::class))->toBeTrue();
	expect($viewer->can('viewAny', Status::class))->toBeTrue();
});

it('allows any role to view a status', function () {
	$status = Status::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $status))->toBeTrue();
	expect($editor->can('view', $status))->toBeTrue();
	expect($viewer->can('view', $status))->toBeTrue();
});

it('allows admin and editor to create statuses', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', Status::class))->toBeTrue();
	expect($editor->can('create', Status::class))->toBeTrue();
});

it('forbids viewer from creating statuses', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', Status::class))->toBeFalse();
});

it('allows admin and editor to update a status', function () {
	$status = Status::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('update', $status))->toBeTrue();
	expect($editor->can('update', $status))->toBeTrue();
});

it('forbids viewer from updating a status', function () {
	$status = Status::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('update', $status))->toBeFalse();
});

it('allows only admin to delete a status', function () {
	$status = Status::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $status))->toBeTrue();
	expect($editor->can('delete', $status))->toBeFalse();
	expect($viewer->can('delete', $status))->toBeFalse();
});

it('allows only admin to restore a status', function () {
	$status = Status::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $status))->toBeTrue();
	expect($editor->can('restore', $status))->toBeFalse();
	expect($viewer->can('restore', $status))->toBeFalse();
});
