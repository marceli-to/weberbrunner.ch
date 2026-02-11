<?php

use App\Models\Award;
use App\Models\Project;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/awards')->assertUnauthorized();
});

it('lists awards', function () {
	asAdmin();
	Award::factory()->count(3)->create();
	$this->getJson('/api/dashboard/awards')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates an award', function () {
	asAdmin();
	$this->postJson('/api/dashboard/awards', [
		'title' => 'Best Design',
		'year' => 2025,
	])
		->assertCreated()
		->assertJsonPath('data.title', 'Best Design');
});

it('validates required fields', function () {
	asAdmin();
	$this->postJson('/api/dashboard/awards', [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['title', 'year']);
});

it('creates an award with project', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson('/api/dashboard/awards', [
		'title' => 'Award',
		'year' => 2025,
		'project_id' => $project->id,
	])
		->assertCreated();
});

it('shows an award', function () {
	asAdmin();
	$award = Award::factory()->create();
	$this->getJson("/api/dashboard/awards/{$award->uuid}")
		->assertOk();
});

it('updates an award', function () {
	asAdmin();
	$award = Award::factory()->create();
	$this->putJson("/api/dashboard/awards/{$award->uuid}", [
		'title' => 'Updated Award',
		'year' => 2024,
	])
		->assertOk()
		->assertJsonPath('data.title', 'Updated Award');
});

it('deletes an award', function () {
	asAdmin();
	$award = Award::factory()->create();
	$this->deleteJson("/api/dashboard/awards/{$award->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted award', function () {
	asAdmin();
	$award = Award::factory()->create();
	$award->delete();
	$this->patchJson("/api/dashboard/awards/{$award->uuid}/restore")
		->assertOk();
	expect(Award::count())->toBe(1);
});

it('reorders awards', function () {
	asAdmin();
	$a = Award::factory()->create();
	$b = Award::factory()->create();
	$this->patchJson('/api/dashboard/awards/reorder', [
		'items' => [
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
