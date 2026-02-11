<?php

use App\Models\NetworkEntry;
use App\Models\User;

it('allows any role to viewAny network entries', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', NetworkEntry::class))->toBeTrue();
	expect($editor->can('viewAny', NetworkEntry::class))->toBeTrue();
	expect($viewer->can('viewAny', NetworkEntry::class))->toBeTrue();
});

it('allows any role to view a network entry', function () {
	$networkEntry = NetworkEntry::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $networkEntry))->toBeTrue();
	expect($editor->can('view', $networkEntry))->toBeTrue();
	expect($viewer->can('view', $networkEntry))->toBeTrue();
});

it('allows admin and editor to create network entries', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', NetworkEntry::class))->toBeTrue();
	expect($editor->can('create', NetworkEntry::class))->toBeTrue();
});

it('forbids viewer from creating network entries', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', NetworkEntry::class))->toBeFalse();
});

it('allows admin and editor to update a network entry', function () {
	$networkEntry = NetworkEntry::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('update', $networkEntry))->toBeTrue();
	expect($editor->can('update', $networkEntry))->toBeTrue();
});

it('forbids viewer from updating a network entry', function () {
	$networkEntry = NetworkEntry::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('update', $networkEntry))->toBeFalse();
});

it('allows only admin to delete a network entry', function () {
	$networkEntry = NetworkEntry::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $networkEntry))->toBeTrue();
	expect($editor->can('delete', $networkEntry))->toBeFalse();
	expect($viewer->can('delete', $networkEntry))->toBeFalse();
});

it('allows only admin to restore a network entry', function () {
	$networkEntry = NetworkEntry::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $networkEntry))->toBeTrue();
	expect($editor->can('restore', $networkEntry))->toBeFalse();
	expect($viewer->can('restore', $networkEntry))->toBeFalse();
});
