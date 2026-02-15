<?php

use App\Models\NetworkEntry;
use App\Models\Section;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/network')->assertUnauthorized();
});

it('lists network entries grouped by section', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'network']);
	NetworkEntry::factory()->count(3)->create(['section_id' => $section->id]);
	$this->getJson('/api/dashboard/network')
		->assertOk()
		->assertJsonCount(1, 'data')
		->assertJsonCount(3, 'data.0.entries');
});

it('creates a network entry', function () {
	asAdmin();
	$section = Section::factory()->create();
	$this->postJson('/api/dashboard/network', [
		'text' => 'Partner Co',
		'section_id' => $section->id,
	])
		->assertCreated()
		->assertJsonPath('data.text', 'Partner Co');
});

it('validates text is required', function () {
	asAdmin();
	$this->postJson('/api/dashboard/network', [])
		->assertUnprocessable()
		->assertJsonValidationErrors('text');
});

it('shows a network entry', function () {
	asAdmin();
	$entry = NetworkEntry::factory()->create();
	$this->getJson("/api/dashboard/network/{$entry->uuid}")
		->assertOk();
});

it('updates a network entry', function () {
	asAdmin();
	$entry = NetworkEntry::factory()->create();
	$this->putJson("/api/dashboard/network/{$entry->uuid}", [
		'text' => 'Updated Partner',
		'section_id' => $entry->section_id,
	])
		->assertOk()
		->assertJsonPath('data.text', 'Updated Partner');
});

it('deletes a network entry', function () {
	asAdmin();
	$entry = NetworkEntry::factory()->create();
	$this->deleteJson("/api/dashboard/network/{$entry->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted network entry', function () {
	asAdmin();
	$entry = NetworkEntry::factory()->create();
	$entry->delete();
	$this->patchJson("/api/dashboard/network/{$entry->uuid}/restore")
		->assertOk();
	expect(NetworkEntry::count())->toBe(1);
});

it('reorders network entries', function () {
	asAdmin();
	$a = NetworkEntry::factory()->create();
	$b = NetworkEntry::factory()->create();
	$this->patchJson('/api/dashboard/network/reorder', [
		'items' => [
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
