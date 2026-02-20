<?php

use App\Models\Project;

it('requires authentication', function () {
	$project = Project::factory()->create();
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/text", [])
		->assertUnauthorized();
});

it('updates project description', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/text", [
		'description' => 'A detailed description',
	])->assertNoContent();
	expect($project->fresh()->description)->toBe('A detailed description');
});

it('updates project short description', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/text", [
		'short_description' => 'A short one',
	])->assertNoContent();
	expect($project->fresh()->short_description)->toBe('A short one');
});

it('updates both descriptions at once', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/text", [
		'description' => 'Long text',
		'short_description' => 'Short text',
	])->assertNoContent();
	$project->refresh();
	expect($project->description)->toBe('Long text');
	expect($project->short_description)->toBe('Short text');
});

it('clears description with null', function () {
	asAdmin();
	$project = Project::factory()->create(['description' => 'Old text']);
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/text", [
		'description' => null,
	])->assertNoContent();
	expect($project->fresh()->description)->toBeNull();
});
