<?php

use App\Models\Block;
use App\Models\BlockLink;
use App\Models\Project;

it('requires authentication', function () {
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/links", [])
		->assertUnauthorized();
});

it('creates an external link', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/links", [
		'title' => 'Example',
		'url' => 'https://example.com',
		'link_type' => 'external',
	])
		->assertCreated()
		->assertJsonPath('data.title', 'Example')
		->assertJsonPath('data.link_type', 'external');
});

it('creates an internal link', function () {
	asAdmin();
	$project = Project::factory()->create();
	$linked = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/links", [
		'link_type' => 'internal',
		'linked_project_id' => $linked->id,
	])
		->assertCreated()
		->assertJsonPath('data.link_type', 'internal');
});

it('validates link_type is required', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/links", [
		'title' => 'No type',
	])
		->assertUnprocessable()
		->assertJsonValidationErrors('link_type');
});

it('validates link_type must be valid', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/links", [
		'link_type' => 'invalid',
	])
		->assertUnprocessable()
		->assertJsonValidationErrors('link_type');
});

it('updates a link', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$link = BlockLink::factory()->create(['block_id' => $block->id]);
	$this->putJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/links/{$link->uuid}", [
		'title' => 'Updated',
		'url' => 'https://updated.com',
		'link_type' => 'external',
	])
		->assertOk()
		->assertJsonPath('data.title', 'Updated');
});

it('deletes a link', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$link = BlockLink::factory()->create(['block_id' => $block->id]);
	$this->deleteJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/links/{$link->uuid}")
		->assertNoContent();
	expect(BlockLink::count())->toBe(0);
});

it('toggles link publish state', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$link = BlockLink::factory()->create(['block_id' => $block->id, 'publish' => true]);
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/links/{$link->uuid}/toggle")
		->assertNoContent();
	expect($link->fresh()->publish)->toBe(false);
});

it('reorders links', function () {
	asAdmin();
	$project = Project::factory()->create();
	$block = Block::factory()->for($project, 'blockable')->create();
	$a = BlockLink::factory()->create(['block_id' => $block->id]);
	$b = BlockLink::factory()->create(['block_id' => $block->id]);
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/blocks/{$block->uuid}/links/reorder", [
		'items' => [
			['uuid' => $a->uuid, 'sort_order' => 2],
			['uuid' => $b->uuid, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
