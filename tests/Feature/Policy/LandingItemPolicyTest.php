<?php

use App\Models\LandingItem;
use App\Models\User;

it('allows any role to viewAny landing items', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', LandingItem::class))->toBeTrue();
	expect($editor->can('viewAny', LandingItem::class))->toBeTrue();
	expect($viewer->can('viewAny', LandingItem::class))->toBeTrue();
});

it('allows any role to view a landing item', function () {
	$item = LandingItem::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $item))->toBeTrue();
	expect($editor->can('view', $item))->toBeTrue();
	expect($viewer->can('view', $item))->toBeTrue();
});

it('allows admin and editor to create landing items', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', LandingItem::class))->toBeTrue();
	expect($editor->can('create', LandingItem::class))->toBeTrue();
});

it('forbids viewer from creating landing items', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', LandingItem::class))->toBeFalse();
});

it('allows admin and editor to delete a landing item', function () {
	$item = LandingItem::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('delete', $item))->toBeTrue();
	expect($editor->can('delete', $item))->toBeTrue();
});

it('forbids viewer from deleting a landing item', function () {
	$item = LandingItem::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('delete', $item))->toBeFalse();
});

it('allows admin and editor to reorder landing items', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('reorder', LandingItem::class))->toBeTrue();
	expect($editor->can('reorder', LandingItem::class))->toBeTrue();
});

it('forbids viewer from reordering landing items', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('reorder', LandingItem::class))->toBeFalse();
});
