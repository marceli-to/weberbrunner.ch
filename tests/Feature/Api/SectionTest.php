<?php

use App\Models\Section;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/sections')->assertUnauthorized();
});

it('lists sections', function () {
	asAdmin();
	Section::factory()->count(3)->create(['type' => 'award']);
	$this->getJson('/api/dashboard/sections')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('filters sections by type', function () {
	asAdmin();
	Section::factory()->count(2)->create(['type' => 'award']);
	Section::factory()->create(['type' => 'jury']);
	$this->getJson('/api/dashboard/sections?type=award')
		->assertOk()
		->assertJsonCount(2, 'data');
});

it('creates a section', function () {
	asAdmin();
	$this->postJson('/api/dashboard/sections', ['title' => '2024', 'type' => 'award'])
		->assertCreated()
		->assertJsonPath('data.title', '2024');
});

it('validates required fields', function () {
	asAdmin();
	$this->postJson('/api/dashboard/sections', [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['title', 'type']);
});

it('validates type must be valid', function () {
	asAdmin();
	$this->postJson('/api/dashboard/sections', ['title' => '2024', 'type' => 'invalid'])
		->assertUnprocessable()
		->assertJsonValidationErrors('type');
});

it('shows a section', function () {
	asAdmin();
	$section = Section::factory()->create();
	$this->getJson("/api/dashboard/sections/{$section->uuid}")
		->assertOk()
		->assertJsonPath('data.title', $section->title);
});

it('updates a section', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'award']);
	$this->putJson("/api/dashboard/sections/{$section->uuid}", ['title' => 'Updated', 'type' => 'award'])
		->assertOk()
		->assertJsonPath('data.title', 'Updated');
});

it('deletes a section', function () {
	asAdmin();
	$section = Section::factory()->create();
	$this->deleteJson("/api/dashboard/sections/{$section->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted section', function () {
	asAdmin();
	$section = Section::factory()->create();
	$section->delete();
	$this->patchJson("/api/dashboard/sections/{$section->uuid}/restore")
		->assertOk();
	expect(Section::count())->toBe(1);
});

it('reorders sections', function () {
	asAdmin();
	$a = Section::factory()->create();
	$b = Section::factory()->create();
	$this->patchJson('/api/dashboard/sections/reorder', [
		'items' => [
			['uuid' => $a->uuid, 'sort_order' => 2],
			['uuid' => $b->uuid, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
