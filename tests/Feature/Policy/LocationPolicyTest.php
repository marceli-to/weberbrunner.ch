<?php

use App\Models\Location;
use App\Models\User;

it('allows any role to viewAny locations', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', Location::class))->toBeTrue();
	expect($editor->can('viewAny', Location::class))->toBeTrue();
	expect($viewer->can('viewAny', Location::class))->toBeTrue();
});

it('allows any role to view a location', function () {
	$location = Location::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $location))->toBeTrue();
	expect($editor->can('view', $location))->toBeTrue();
	expect($viewer->can('view', $location))->toBeTrue();
});

it('allows admin and editor to create locations', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', Location::class))->toBeTrue();
	expect($editor->can('create', Location::class))->toBeTrue();
});

it('forbids viewer from creating locations', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', Location::class))->toBeFalse();
});

it('allows admin and editor to update a location', function () {
	$location = Location::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('update', $location))->toBeTrue();
	expect($editor->can('update', $location))->toBeTrue();
});

it('forbids viewer from updating a location', function () {
	$location = Location::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('update', $location))->toBeFalse();
});

it('allows only admin to delete a location', function () {
	$location = Location::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $location))->toBeTrue();
	expect($editor->can('delete', $location))->toBeFalse();
	expect($viewer->can('delete', $location))->toBeFalse();
});

it('allows only admin to restore a location', function () {
	$location = Location::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $location))->toBeTrue();
	expect($editor->can('restore', $location))->toBeFalse();
	expect($viewer->can('restore', $location))->toBeFalse();
});
