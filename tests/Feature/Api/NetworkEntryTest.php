<?php

use App\Models\NetworkEntry;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/network')->assertUnauthorized();
});

it('lists network entries', function () {
	asAdmin();
	NetworkEntry::factory()->count(3)->create();
	$this->getJson('/api/dashboard/network')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a network entry', function () {
	asAdmin();
	$this->postJson('/api/dashboard/network', ['title' => 'Partner Co'])
		->assertCreated()
		->assertJsonPath('data.title', 'Partner Co');
});

it('validates title is required', function () {
	asAdmin();
	$this->postJson('/api/dashboard/network', [])
		->assertUnprocessable()
		->assertJsonValidationErrors('title');
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
	$this->putJson("/api/dashboard/network/{$entry->uuid}", ['title' => 'Updated Partner'])
		->assertOk()
		->assertJsonPath('data.title', 'Updated Partner');
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
