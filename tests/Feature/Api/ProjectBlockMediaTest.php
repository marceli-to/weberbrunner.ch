<?php

use App\Models\Block;
use App\Models\Media;
use App\Models\Project;

it('requires authentication', function () {
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/media/select", [])
		->assertUnauthorized();
});

it('selects media for a block', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$media = $project->media()->create(Media::factory()->make()->toArray());
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/media/select", [
		'media_uuids' => [$media->uuid],
	])
		->assertOk();
	expect($block->media()->count())->toBe(1);
});

it('validates media_uuids is required', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/media/select", [])
		->assertUnprocessable()
		->assertJsonValidationErrors('media_uuids');
});

it('validates media_uuids must exist', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/media/select", [
		'media_uuids' => ['non-existent-uuid'],
	])
		->assertUnprocessable()
		->assertJsonValidationErrors('media_uuids.0');
});

it('detaches media from a block', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$media = $block->media()->create(Media::factory()->make()->toArray());
	$this->deleteJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/media/{$media->uuid}")
		->assertNoContent();
	expect($block->media()->count())->toBe(0);
});
