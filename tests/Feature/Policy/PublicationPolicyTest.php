<?php

use App\Models\Publication;
use App\Models\User;

it('allows any role to viewAny publications', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', Publication::class))->toBeTrue();
	expect($editor->can('viewAny', Publication::class))->toBeTrue();
	expect($viewer->can('viewAny', Publication::class))->toBeTrue();
});

it('allows any role to view a publication', function () {
	$publication = Publication::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $publication))->toBeTrue();
	expect($editor->can('view', $publication))->toBeTrue();
	expect($viewer->can('view', $publication))->toBeTrue();
});

it('allows admin and editor to create publications', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', Publication::class))->toBeTrue();
	expect($editor->can('create', Publication::class))->toBeTrue();
});

it('forbids viewer from creating publications', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', Publication::class))->toBeFalse();
});

it('allows admin and editor to update a publication', function () {
	$publication = Publication::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('update', $publication))->toBeTrue();
	expect($editor->can('update', $publication))->toBeTrue();
});

it('forbids viewer from updating a publication', function () {
	$publication = Publication::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('update', $publication))->toBeFalse();
});

it('allows only admin to delete a publication', function () {
	$publication = Publication::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $publication))->toBeTrue();
	expect($editor->can('delete', $publication))->toBeFalse();
	expect($viewer->can('delete', $publication))->toBeFalse();
});

it('allows only admin to restore a publication', function () {
	$publication = Publication::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $publication))->toBeTrue();
	expect($editor->can('restore', $publication))->toBeFalse();
	expect($viewer->can('restore', $publication))->toBeFalse();
});

it('allows admin and editor to reorder publications', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('reorder', Publication::class))->toBeTrue();
	expect($editor->can('reorder', Publication::class))->toBeTrue();
});

it('forbids viewer from reordering publications', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('reorder', Publication::class))->toBeFalse();
});
