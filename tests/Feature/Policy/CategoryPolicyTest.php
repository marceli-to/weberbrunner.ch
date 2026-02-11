<?php

use App\Models\Category;
use App\Models\User;

it('allows any role to viewAny categories', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', Category::class))->toBeTrue();
	expect($editor->can('viewAny', Category::class))->toBeTrue();
	expect($viewer->can('viewAny', Category::class))->toBeTrue();
});

it('allows any role to view a category', function () {
	$category = Category::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $category))->toBeTrue();
	expect($editor->can('view', $category))->toBeTrue();
	expect($viewer->can('view', $category))->toBeTrue();
});

it('allows admin and editor to create categories', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', Category::class))->toBeTrue();
	expect($editor->can('create', Category::class))->toBeTrue();
});

it('forbids viewer from creating categories', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', Category::class))->toBeFalse();
});

it('allows admin and editor to update a category', function () {
	$category = Category::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('update', $category))->toBeTrue();
	expect($editor->can('update', $category))->toBeTrue();
});

it('forbids viewer from updating a category', function () {
	$category = Category::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('update', $category))->toBeFalse();
});

it('allows only admin to delete a category', function () {
	$category = Category::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $category))->toBeTrue();
	expect($editor->can('delete', $category))->toBeFalse();
	expect($viewer->can('delete', $category))->toBeFalse();
});

it('allows only admin to restore a category', function () {
	$category = Category::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $category))->toBeTrue();
	expect($editor->can('restore', $category))->toBeFalse();
	expect($viewer->can('restore', $category))->toBeFalse();
});
