<?php

use App\Models\Talk;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/talks')->assertUnauthorized();
});

it('lists talks', function () {
	asAdmin();
	Talk::factory()->count(3)->create();
	$this->getJson('/api/dashboard/talks')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a talk', function () {
	asAdmin();
	$this->postJson('/api/dashboard/talks', [
		'title' => 'My Talk',
		'date' => '2026-03-15',
	])
		->assertCreated()
		->assertJsonPath('data.title', 'My Talk');
});

it('validates required fields', function () {
	asAdmin();
	$this->postJson('/api/dashboard/talks', [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['title', 'date']);
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
		'title' => 'Updated Talk',
		'date' => '2026-06-01',
	])
		->assertOk()
		->assertJsonPath('data.title', 'Updated Talk');
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
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
