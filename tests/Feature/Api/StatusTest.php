<?php

use App\Models\Status;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/statuses')->assertUnauthorized();
});

it('lists statuses', function () {
	asAdmin();
	Status::factory()->count(3)->create();
	$this->getJson('/api/dashboard/statuses')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a status', function () {
	asAdmin();
	$this->postJson('/api/dashboard/statuses', ['title' => 'In Progress'])
		->assertCreated()
		->assertJsonPath('data.title', 'In Progress');
});

it('validates title is required', function () {
	asAdmin();
	$this->postJson('/api/dashboard/statuses', [])
		->assertUnprocessable()
		->assertJsonValidationErrors('title');
});

it('shows a status', function () {
	asAdmin();
	$status = Status::factory()->create();
	$this->getJson("/api/dashboard/statuses/{$status->uuid}")
		->assertOk();
});

it('updates a status', function () {
	asAdmin();
	$status = Status::factory()->create();
	$this->putJson("/api/dashboard/statuses/{$status->uuid}", ['title' => 'Completed'])
		->assertOk()
		->assertJsonPath('data.title', 'Completed');
});

it('deletes a status', function () {
	asAdmin();
	$status = Status::factory()->create();
	$this->deleteJson("/api/dashboard/statuses/{$status->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted status', function () {
	asAdmin();
	$status = Status::factory()->create();
	$status->delete();
	$this->patchJson("/api/dashboard/statuses/{$status->uuid}/restore")
		->assertOk();
	expect(Status::count())->toBe(1);
});

it('reorders statuses', function () {
	asAdmin();
	$a = Status::factory()->create();
	$b = Status::factory()->create();
	$this->patchJson('/api/dashboard/statuses/reorder', [
		'items' => [
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
