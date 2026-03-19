<?php

use App\Models\Publication;
use App\Models\PublicationBlock;

it('requires authentication', function () {
	$publication = Publication::factory()->create();
	$this->getJson("/api/dashboard/publications/{$publication->uuid}/blocks")->assertUnauthorized();
});

it('lists blocks for a publication', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	PublicationBlock::factory()->count(3)->create(['publication_id' => $publication->id]);
	$this->getJson("/api/dashboard/publications/{$publication->uuid}/blocks")
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a text block', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$this->postJson("/api/dashboard/publications/{$publication->uuid}/blocks", [
		'type' => 'text',
		'title' => 'Introduction',
		'content' => 'Some content',
	])
		->assertCreated()
		->assertJsonPath('data.title', 'Introduction')
		->assertJsonPath('data.type', 'text');
});

it('validates type is required', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$this->postJson("/api/dashboard/publications/{$publication->uuid}/blocks", [
		'title' => 'Missing type',
	])
		->assertUnprocessable()
		->assertJsonValidationErrors('type');
});

it('validates type must be valid', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$this->postJson("/api/dashboard/publications/{$publication->uuid}/blocks", [
		'type' => 'invalid',
		'title' => 'Bad type',
	])
		->assertUnprocessable()
		->assertJsonValidationErrors('type');
});

it('updates a block', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$block = PublicationBlock::factory()->create(['publication_id' => $publication->id, 'type' => 'text']);
	$this->putJson("/api/dashboard/publications/{$publication->uuid}/blocks/{$block->uuid}", [
		'title' => 'Updated',
		'content' => 'New content',
	])
		->assertOk()
		->assertJsonPath('data.title', 'Updated');
});

it('deletes a block', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$block = PublicationBlock::factory()->create(['publication_id' => $publication->id]);
	$this->deleteJson("/api/dashboard/publications/{$publication->uuid}/blocks/{$block->uuid}")
		->assertNoContent();
	expect(PublicationBlock::count())->toBe(0);
});

it('reorders blocks', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$a = PublicationBlock::factory()->create(['publication_id' => $publication->id]);
	$b = PublicationBlock::factory()->create(['publication_id' => $publication->id]);
	$this->patchJson("/api/dashboard/publications/{$publication->uuid}/blocks/reorder", [
		'items' => [
			['uuid' => $a->uuid, 'sort_order' => 2],
			['uuid' => $b->uuid, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
