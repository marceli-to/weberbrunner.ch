<?php

use App\Models\Location;
use App\Models\Publication;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/publications')->assertUnauthorized();
});

it('lists publications', function () {
	asAdmin();
	Publication::factory()->count(3)->create();
	$this->getJson('/api/dashboard/publications')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a publication', function () {
	asAdmin();
	$this->postJson('/api/dashboard/publications', ['title' => 'New Publication'])
		->assertCreated()
		->assertJsonPath('data.title', 'New Publication');
});

it('creates a publication with location', function () {
	asAdmin();
	$location = Location::factory()->create();
	$this->postJson('/api/dashboard/publications', [
		'title' => 'Located Publication',
		'location_id' => $location->id,
	])
		->assertCreated()
		->assertJsonPath('data.location_id', $location->id);
});

it('validates title is required', function () {
	asAdmin();
	$this->postJson('/api/dashboard/publications', [])
		->assertUnprocessable()
		->assertJsonValidationErrors('title');
});

it('shows a publication', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$this->getJson("/api/dashboard/publications/{$publication->uuid}")
		->assertOk()
		->assertJsonPath('data.title', $publication->title);
});

it('updates a publication', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$this->putJson("/api/dashboard/publications/{$publication->uuid}", [
		'title' => 'Updated',
	])
		->assertOk()
		->assertJsonPath('data.title', 'Updated');
});

it('deletes a publication', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$this->deleteJson("/api/dashboard/publications/{$publication->uuid}")
		->assertNoContent();
	expect(Publication::count())->toBe(0);
	expect(Publication::withTrashed()->count())->toBe(1);
});

it('restores a soft-deleted publication', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$publication->delete();
	$this->patchJson("/api/dashboard/publications/{$publication->uuid}/restore")
		->assertOk();
	expect(Publication::count())->toBe(1);
});

it('toggles publish status', function () {
	asAdmin();
	$publication = Publication::factory()->create(['publish' => true]);
	$this->patchJson("/api/dashboard/publications/{$publication->uuid}/toggle")
		->assertNoContent();
	expect($publication->fresh()->publish)->toBeFalse();
});

it('reorders publications', function () {
	asAdmin();
	$a = Publication::factory()->create();
	$b = Publication::factory()->create();
	$this->patchJson('/api/dashboard/publications/reorder', [
		'items' => [
			['uuid' => $a->uuid, 'sort_order' => 2],
			['uuid' => $b->uuid, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
	expect($b->fresh()->sort_order)->toBe(1);
});

it('forbids viewer from creating a publication', function () {
	asViewer();
	$this->postJson('/api/dashboard/publications', ['title' => 'Denied'])
		->assertForbidden();
});

it('forbids editor from deleting a publication', function () {
	asEditor();
	$publication = Publication::factory()->create();
	$this->deleteJson("/api/dashboard/publications/{$publication->uuid}")
		->assertForbidden();
});
