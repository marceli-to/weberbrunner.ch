<?php

use App\Models\Publication;
use App\Models\PublicationAttribute;

it('requires authentication', function () {
	$publication = Publication::factory()->create();
	$this->getJson("/api/dashboard/publications/{$publication->uuid}/attributes")->assertUnauthorized();
});

it('lists attributes for a publication', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	PublicationAttribute::factory()->count(3)->create(['publication_id' => $publication->id]);
	$this->getJson("/api/dashboard/publications/{$publication->uuid}/attributes")
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates an attribute', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$this->postJson("/api/dashboard/publications/{$publication->uuid}/attributes", [
		'key' => 'Author',
		'value' => 'John Doe',
	])
		->assertCreated()
		->assertJsonPath('data.key', 'Author');
});

it('validates required fields', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$this->postJson("/api/dashboard/publications/{$publication->uuid}/attributes", [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['key', 'value']);
});

it('updates an attribute', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$attribute = PublicationAttribute::factory()->create(['publication_id' => $publication->id]);
	$this->putJson("/api/dashboard/publications/{$publication->uuid}/attributes/{$attribute->uuid}", [
		'key' => 'Updated',
		'value' => 'New Value',
	])
		->assertOk()
		->assertJsonPath('data.key', 'Updated');
});

it('deletes an attribute', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$attribute = PublicationAttribute::factory()->create(['publication_id' => $publication->id]);
	$this->deleteJson("/api/dashboard/publications/{$publication->uuid}/attributes/{$attribute->uuid}")
		->assertNoContent();
	expect(PublicationAttribute::count())->toBe(0);
});

it('reorders attributes', function () {
	asAdmin();
	$publication = Publication::factory()->create();
	$a = PublicationAttribute::factory()->create(['publication_id' => $publication->id]);
	$b = PublicationAttribute::factory()->create(['publication_id' => $publication->id]);
	$this->patchJson("/api/dashboard/publications/{$publication->uuid}/attributes/reorder", [
		'items' => [
			['uuid' => $a->uuid, 'sort_order' => 2],
			['uuid' => $b->uuid, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
