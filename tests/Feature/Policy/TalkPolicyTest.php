<?php

use App\Models\Talk;
use App\Models\User;

it('allows any role to viewAny talks', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', Talk::class))->toBeTrue();
	expect($editor->can('viewAny', Talk::class))->toBeTrue();
	expect($viewer->can('viewAny', Talk::class))->toBeTrue();
});

it('allows any role to view a talk', function () {
	$talk = Talk::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $talk))->toBeTrue();
	expect($editor->can('view', $talk))->toBeTrue();
	expect($viewer->can('view', $talk))->toBeTrue();
});

it('allows admin and editor to create talks', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', Talk::class))->toBeTrue();
	expect($editor->can('create', Talk::class))->toBeTrue();
});

it('forbids viewer from creating talks', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', Talk::class))->toBeFalse();
});

it('allows admin and editor to update a talk', function () {
	$talk = Talk::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('update', $talk))->toBeTrue();
	expect($editor->can('update', $talk))->toBeTrue();
});

it('forbids viewer from updating a talk', function () {
	$talk = Talk::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('update', $talk))->toBeFalse();
});

it('allows only admin to delete a talk', function () {
	$talk = Talk::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $talk))->toBeTrue();
	expect($editor->can('delete', $talk))->toBeFalse();
	expect($viewer->can('delete', $talk))->toBeFalse();
});

it('allows only admin to restore a talk', function () {
	$talk = Talk::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $talk))->toBeTrue();
	expect($editor->can('restore', $talk))->toBeFalse();
	expect($viewer->can('restore', $talk))->toBeFalse();
});
