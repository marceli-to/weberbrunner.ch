<?php

use App\Models\Block;
use App\Models\Project;

it('requires authentication', function () {
	$project = Project::factory()->create();
	$this->getJson("/api/dashboard/projects/{$project->uuid}/blocks")->assertUnauthorized();
});

it('lists blocks for a project', function () {
	asAdmin();
	$project = Project::factory()->create();
	Block::factory()->count(3)->for($project, 'blockable')->create();
	$this->getJson("/api/dashboard/projects/{$project->uuid}/blocks")
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a text block', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks", [
		'type' => 'text',
		'title' => 'About',
		'content' => 'Some content',
	])
		->assertCreated()
		->assertJsonPath('data.title', 'About')
		->assertJsonPath('data.type', 'text');
});

it('creates a slider block', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks", [
		'type' => 'slider',
		'title' => 'Gallery',
	])
		->assertCreated()
		->assertJsonPath('data.type', 'slider');
});

it('validates type is required', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks", [
		'title' => 'Missing type',
	])
		->assertUnprocessable()
		->assertJsonValidationErrors('type');
});

it('validates type must be valid', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks", [
		'type' => 'invalid',
		'title' => 'Bad type',
	])
		->assertUnprocessable()
		->assertJsonValidationErrors('type');
});

it('validates title is required for non-fixed-slider types', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks", [
		'type' => 'text',
	])
		->assertUnprocessable()
		->assertJsonValidationErrors('title');
});

it('allows nullable title for fixed-slider type', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks", [
		'type' => 'fixed-slider',
	])
		->assertCreated();
});

it('updates a block', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create(['type' => 'text']);
	$this->putJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}", [
		'type' => 'text',
		'title' => 'Updated',
		'content' => 'New content',
	])
		->assertOk()
		->assertJsonPath('data.title', 'Updated');
});

it('deletes a block', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$this->deleteJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}")
		->assertNoContent();
	expect(Block::count())->toBe(0);
});

it('reorders blocks', function () {
	asAdmin();
	$project = Project::factory()->create();
	$a = Block::factory()->for($project, 'blockable')->create();
	$b = Block::factory()->for($project, 'blockable')->create();
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/blocks/reorder", [
		'items' => [
			['uuid' => $a->uuid, 'sort_order' => 2],
			['uuid' => $b->uuid, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
