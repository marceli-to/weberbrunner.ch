<?php

use App\Models\Jury;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/jury')->assertUnauthorized();
});

it('lists jury entries', function () {
	asAdmin();
	Jury::factory()->count(3)->create();
	$this->getJson('/api/dashboard/jury')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a jury entry', function () {
	asAdmin();
	$this->postJson('/api/dashboard/jury', [
		'title' => 'Design Award Jury',
		'year' => 2025,
	])
		->assertCreated()
		->assertJsonPath('data.title', 'Design Award Jury');
});

it('validates required fields', function () {
	asAdmin();
	$this->postJson('/api/dashboard/jury', [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['title', 'year']);
});

it('shows a jury entry', function () {
	asAdmin();
	$jury = Jury::factory()->create();
	$this->getJson("/api/dashboard/jury/{$jury->uuid}")
		->assertOk();
});

it('updates a jury entry', function () {
	asAdmin();
	$jury = Jury::factory()->create();
	$this->putJson("/api/dashboard/jury/{$jury->uuid}", [
		'title' => 'Updated Jury',
		'year' => 2024,
	])
		->assertOk()
		->assertJsonPath('data.title', 'Updated Jury');
});

it('deletes a jury entry', function () {
	asAdmin();
	$jury = Jury::factory()->create();
	$this->deleteJson("/api/dashboard/jury/{$jury->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted jury entry', function () {
	asAdmin();
	$jury = Jury::factory()->create();
	$jury->delete();
	$this->patchJson("/api/dashboard/jury/{$jury->uuid}/restore")
		->assertOk();
	expect(Jury::count())->toBe(1);
});

it('reorders jury entries', function () {
	asAdmin();
	$a = Jury::factory()->create();
	$b = Jury::factory()->create();
	$this->patchJson('/api/dashboard/jury/reorder', [
		'items' => [
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
