<?php

use App\Models\Media;
use App\Models\Project;
use App\Models\Publication;
use App\Models\TeamMember;
use App\Models\User;

it('allows admin and editor to create and update media', function () {
	$media = Media::factory()->create();
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('create', Media::class))->toBeTrue();
	expect($editor->can('create', Media::class))->toBeTrue();
	expect($viewer->can('create', Media::class))->toBeFalse();
	expect($editor->can('update', $media))->toBeTrue();
	expect($viewer->can('update', $media))->toBeFalse();
});

it('allows admin and editor to delete a team member portrait', function () {
	$teamMember = TeamMember::factory()->create();
	$portrait = $teamMember->media()->save(Media::factory()->make());
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $portrait))->toBeTrue();
	expect($editor->can('delete', $portrait))->toBeTrue();
	expect($viewer->can('delete', $portrait))->toBeFalse();
});

it('allows admin and editor to delete a project image', function () {
	$project = Project::factory()->create();
	$image = $project->media()->save(Media::factory()->make());
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	$viewer = User::factory()->create(['role' => 'viewer']);
	expect($admin->can('delete', $image))->toBeTrue();
	expect($editor->can('delete', $image))->toBeTrue();
	expect($viewer->can('delete', $image))->toBeFalse();
});

it('allows only admin to delete media of other types', function () {
	$publication = Publication::factory()->create();
	$image = $publication->media()->save(Media::factory()->make());
	$admin = User::factory()->create(['role' => 'admin']);
	$editor = User::factory()->create(['role' => 'editor']);
	expect($admin->can('delete', $image))->toBeTrue();
	expect($editor->can('delete', $image))->toBeFalse();
});
