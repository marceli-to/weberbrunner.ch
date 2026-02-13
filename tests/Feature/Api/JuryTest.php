<?php

use App\Models\Jury;
use App\Models\Section;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/jury')->assertUnauthorized();
});

it('lists jury entries', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'jury']);
	Jury::factory()->count(3)->create(['section_id' => $section->id]);
	$this->getJson('/api/dashboard/jury')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a jury entry', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'jury']);
	$this->postJson('/api/dashboard/jury', [
		'title' => 'Design Award Jury',
		'section_id' => $section->id,
	])
		->assertCreated()
		->assertJsonPath('data.title', 'Design Award Jury');
});

it('validates required fields', function () {
	asAdmin();
	$this->postJson('/api/dashboard/jury', [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['title', 'section_id']);
});

it('shows a jury entry', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'jury']);
	$jury = Jury::factory()->create(['section_id' => $section->id]);
	$this->getJson("/api/dashboard/jury/{$jury->uuid}")
		->assertOk();
});

it('updates a jury entry', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'jury']);
	$jury = Jury::factory()->create(['section_id' => $section->id]);
	$this->putJson("/api/dashboard/jury/{$jury->uuid}", [
		'title' => 'Updated Jury',
		'section_id' => $section->id,
	])
		->assertOk()
		->assertJsonPath('data.title', 'Updated Jury');
});

it('deletes a jury entry', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'jury']);
	$jury = Jury::factory()->create(['section_id' => $section->id]);
	$this->deleteJson("/api/dashboard/jury/{$jury->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted jury entry', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'jury']);
	$jury = Jury::factory()->create(['section_id' => $section->id]);
	$jury->delete();
	$this->patchJson("/api/dashboard/jury/{$jury->uuid}/restore")
		->assertOk();
	expect(Jury::count())->toBe(1);
});

it('reorders jury entries', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'jury']);
	$a = Jury::factory()->create(['section_id' => $section->id]);
	$b = Jury::factory()->create(['section_id' => $section->id]);
	$this->patchJson('/api/dashboard/jury/reorder', [
		'items' => [
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
