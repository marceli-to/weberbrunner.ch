<?php

use App\Models\Project;
use App\Models\User;

it('allows any role to viewAny projects', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', Project::class))->toBeTrue();
	expect($editor->can('viewAny', Project::class))->toBeTrue();
	expect($viewer->can('viewAny', Project::class))->toBeTrue();
});

it('allows any role to view a project', function () {
	$project = Project::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $project))->toBeTrue();
	expect($editor->can('view', $project))->toBeTrue();
	expect($viewer->can('view', $project))->toBeTrue();
});

it('allows admin and editor to create projects', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', Project::class))->toBeTrue();
	expect($editor->can('create', Project::class))->toBeTrue();
});

it('forbids viewer from creating projects', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', Project::class))->toBeFalse();
});

it('allows admin and editor to update a project', function () {
	$project = Project::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('update', $project))->toBeTrue();
	expect($editor->can('update', $project))->toBeTrue();
});

it('forbids viewer from updating a project', function () {
	$project = Project::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('update', $project))->toBeFalse();
});

it('allows only admin to delete a project', function () {
	$project = Project::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $project))->toBeTrue();
	expect($editor->can('delete', $project))->toBeFalse();
	expect($viewer->can('delete', $project))->toBeFalse();
});

it('allows only admin to restore a project', function () {
	$project = Project::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $project))->toBeTrue();
	expect($editor->can('restore', $project))->toBeFalse();
	expect($viewer->can('restore', $project))->toBeFalse();
});
