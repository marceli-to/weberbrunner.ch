<?php

use App\Models\Media;
use App\Models\Project;

it('lets an admin publish a project via update', function () {
	asAdmin();
	$project = Project::factory()->create(['publish' => false]);

	$this->putJson("/api/dashboard/projects/{$project->uuid}", [
		'title' => $project->title,
		'number' => $project->number,
		'publish' => true,
	])->assertOk();

	expect($project->fresh()->publish)->toBeTrue();
});

it('ignores the publish flag when an editor updates a project', function () {
	asEditor();
	$project = Project::factory()->create(['publish' => false]);

	$this->putJson("/api/dashboard/projects/{$project->uuid}", [
		'title' => 'Editor Edit',
		'number' => $project->number,
		'publish' => true,
	])->assertOk();

	expect($project->fresh()->publish)->toBeFalse();
	expect($project->fresh()->title)->toBe('Editor Edit');
});

it('does not let an editor unpublish a project via update', function () {
	asAdmin();
	$project = Project::factory()->create(['publish' => true]);

	asEditor();
	$this->putJson("/api/dashboard/projects/{$project->uuid}", [
		'title' => $project->title,
		'number' => $project->number,
		'publish' => false,
	])->assertOk();

	expect($project->fresh()->publish)->toBeTrue();
});

it('lets an admin toggle media publish', function () {
	asAdmin();
	$media = Media::factory()->create(['publish' => false]);

	$this->patchJson("/api/dashboard/media/{$media->uuid}/publish")
		->assertOk();

	expect($media->fresh()->publish)->toBeTrue();
});

it('forbids an editor from toggling media publish', function () {
	asEditor();
	$media = Media::factory()->create(['publish' => false]);

	$this->patchJson("/api/dashboard/media/{$media->uuid}/publish")
		->assertForbidden();

	expect($media->fresh()->publish)->toBeFalse();
});
