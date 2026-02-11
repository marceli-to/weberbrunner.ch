<?php

use App\Models\TeamMember;
use App\Models\User;

it('allows any role to viewAny team members', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', TeamMember::class))->toBeTrue();
	expect($editor->can('viewAny', TeamMember::class))->toBeTrue();
	expect($viewer->can('viewAny', TeamMember::class))->toBeTrue();
});

it('allows any role to view a team member', function () {
	$teamMember = TeamMember::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $teamMember))->toBeTrue();
	expect($editor->can('view', $teamMember))->toBeTrue();
	expect($viewer->can('view', $teamMember))->toBeTrue();
});

it('allows admin and editor to create team members', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', TeamMember::class))->toBeTrue();
	expect($editor->can('create', TeamMember::class))->toBeTrue();
});

it('forbids viewer from creating team members', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', TeamMember::class))->toBeFalse();
});

it('allows admin and editor to update a team member', function () {
	$teamMember = TeamMember::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('update', $teamMember))->toBeTrue();
	expect($editor->can('update', $teamMember))->toBeTrue();
});

it('forbids viewer from updating a team member', function () {
	$teamMember = TeamMember::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('update', $teamMember))->toBeFalse();
});

it('allows only admin to delete a team member', function () {
	$teamMember = TeamMember::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $teamMember))->toBeTrue();
	expect($editor->can('delete', $teamMember))->toBeFalse();
	expect($viewer->can('delete', $teamMember))->toBeFalse();
});

it('allows only admin to restore a team member', function () {
	$teamMember = TeamMember::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $teamMember))->toBeTrue();
	expect($editor->can('restore', $teamMember))->toBeFalse();
	expect($viewer->can('restore', $teamMember))->toBeFalse();
});
