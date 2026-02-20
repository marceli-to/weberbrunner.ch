<?php

use App\Models\Section;
use App\Models\Talk;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/talks')->assertUnauthorized();
});

it('lists talks grouped by section', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'talk']);
	Talk::factory()->count(3)->create(['section_id' => $section->id]);
	$this->getJson('/api/dashboard/talks')
		->assertOk()
		->assertJsonCount(1, 'data')
		->assertJsonCount(3, 'data.0.entries');
});

it('creates a talk', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'talk']);
	$this->postJson('/api/dashboard/talks', [
		'text' => 'My Talk',
		'section_id' => $section->id,
	])
		->assertCreated()
		->assertJsonPath('data.text', 'My Talk');
});

it('validates required fields', function () {
	asAdmin();
	$this->postJson('/api/dashboard/talks', [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['text', 'section_id']);
});

it('shows a talk', function () {
	asAdmin();
	$talk = Talk::factory()->create();
	$this->getJson("/api/dashboard/talks/{$talk->uuid}")
		->assertOk();
});

it('updates a talk', function () {
	asAdmin();
	$talk = Talk::factory()->create();
	$this->putJson("/api/dashboard/talks/{$talk->uuid}", [
		'text' => 'Updated Talk',
		'section_id' => $talk->section_id,
	])
		->assertOk()
		->assertJsonPath('data.text', 'Updated Talk');
});

it('deletes a talk', function () {
	asAdmin();
	$talk = Talk::factory()->create();
	$this->deleteJson("/api/dashboard/talks/{$talk->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted talk', function () {
	asAdmin();
	$talk = Talk::factory()->create();
	$talk->delete();
	$this->patchJson("/api/dashboard/talks/{$talk->uuid}/restore")
		->assertOk();
	expect(Talk::count())->toBe(1);
});

it('reorders talks', function () {
	asAdmin();
	$a = Talk::factory()->create();
	$b = Talk::factory()->create();
	$this->patchJson('/api/dashboard/talks/reorder', [
		'items' => [
			['uuid' => $a->uuid, 'sort_order' => 2],
			['uuid' => $b->uuid, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
