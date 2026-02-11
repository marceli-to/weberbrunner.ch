<?php

use App\Models\Job;
use App\Models\User;

it('allows any role to viewAny jobs', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('viewAny', Job::class))->toBeTrue();
	expect($editor->can('viewAny', Job::class))->toBeTrue();
	expect($viewer->can('viewAny', Job::class))->toBeTrue();
});

it('allows any role to view a job', function () {
	$job = Job::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('view', $job))->toBeTrue();
	expect($editor->can('view', $job))->toBeTrue();
	expect($viewer->can('view', $job))->toBeTrue();
});

it('allows admin and editor to create jobs', function () {
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('create', Job::class))->toBeTrue();
	expect($editor->can('create', Job::class))->toBeTrue();
});

it('forbids viewer from creating jobs', function () {
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('create', Job::class))->toBeFalse();
});

it('allows admin and editor to update a job', function () {
	$job = Job::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('update', $job))->toBeTrue();
	expect($editor->can('update', $job))->toBeTrue();
});

it('forbids viewer from updating a job', function () {
	$job = Job::factory()->create();
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($viewer->can('update', $job))->toBeFalse();
});

it('allows only admin to delete a job', function () {
	$job = Job::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $job))->toBeTrue();
	expect($editor->can('delete', $job))->toBeFalse();
	expect($viewer->can('delete', $job))->toBeFalse();
});

it('allows only admin to restore a job', function () {
	$job = Job::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('restore', $job))->toBeTrue();
	expect($editor->can('restore', $job))->toBeFalse();
	expect($viewer->can('restore', $job))->toBeFalse();
});
