<?php

use App\Models\Location;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/locations')->assertUnauthorized();
});

it('lists locations ordered by sort_order', function () {
	asAdmin();
	Location::factory()->count(3)->create();
	$this->getJson('/api/dashboard/locations')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a location', function () {
	asAdmin();
	$this->postJson('/api/dashboard/locations', ['title' => 'Zurich'])
		->assertCreated()
		->assertJsonPath('data.title', 'Zurich')
		->assertJsonPath('data.slug', 'zurich');
});

it('validates title is required on store', function () {
	asAdmin();
	$this->postJson('/api/dashboard/locations', [])
		->assertUnprocessable()
		->assertJsonValidationErrors('title');
});

it('shows a location', function () {
	asAdmin();
	$location = Location::factory()->create();
	$this->getJson("/api/dashboard/locations/{$location->uuid}")
		->assertOk()
		->assertJsonPath('data.title', $location->title);
});

it('updates a location', function () {
	asAdmin();
	$location = Location::factory()->create();
	$this->putJson("/api/dashboard/locations/{$location->uuid}", ['title' => 'Berlin'])
		->assertOk()
		->assertJsonPath('data.title', 'Berlin')
		->assertJsonPath('data.slug', 'berlin');
});

it('deletes a location', function () {
	asAdmin();
	$location = Location::factory()->create();
	$this->deleteJson("/api/dashboard/locations/{$location->uuid}")
		->assertNoContent();
	expect(Location::count())->toBe(0);
});

it('restores a soft-deleted location', function () {
	asAdmin();
	$location = Location::factory()->create();
	$location->delete();
	$this->patchJson("/api/dashboard/locations/{$location->uuid}/restore")
		->assertOk();
	expect(Location::count())->toBe(1);
});

it('reorders locations', function () {
	asAdmin();
	$a = Location::factory()->create();
	$b = Location::factory()->create();
	$this->patchJson('/api/dashboard/locations/reorder', [
		'items' => [
			['uuid' => $a->uuid, 'sort_order' => 2],
			['uuid' => $b->uuid, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
	expect($b->fresh()->sort_order)->toBe(1);
});
