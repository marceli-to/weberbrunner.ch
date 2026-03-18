<?php

use App\Models\LandingItem;
use App\Models\Project;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/landing')->assertUnauthorized();
});

it('lists landing items grouped by column', function () {
	asAdmin();
	LandingItem::factory()->create(['column' => 1]);
	LandingItem::factory()->create(['column' => 2]);
	LandingItem::factory()->create(['column' => 3]);

	$this->getJson('/api/dashboard/landing')
		->assertOk()
		->assertJsonCount(1, 'data.1')
		->assertJsonCount(1, 'data.2')
		->assertJsonCount(1, 'data.3');
});

it('returns empty columns when no items exist', function () {
	asAdmin();
	$this->getJson('/api/dashboard/landing')
		->assertOk()
		->assertJsonCount(0, 'data.1')
		->assertJsonCount(0, 'data.2')
		->assertJsonCount(0, 'data.3');
});

it('stores a landing item', function () {
	asAdmin();
	$project = Project::factory()->create();

	$this->postJson('/api/dashboard/landing', [
		'project_id' => $project->id,
		'column' => 2,
	])
		->assertCreated()
		->assertJsonPath('data.column', 2)
		->assertJsonPath('data.project_id', $project->id);

	expect(LandingItem::count())->toBe(1);
});

it('computes sort_order per column', function () {
	asAdmin();
	$p1 = Project::factory()->create();
	$p2 = Project::factory()->create();
	$p3 = Project::factory()->create();

	$this->postJson('/api/dashboard/landing', ['project_id' => $p1->id, 'column' => 1]);
	$this->postJson('/api/dashboard/landing', ['project_id' => $p2->id, 'column' => 1]);
	$this->postJson('/api/dashboard/landing', ['project_id' => $p3->id, 'column' => 2]);

	$items = LandingItem::orderBy('id')->get();
	expect($items[0]->sort_order)->toBe(1);
	expect($items[1]->sort_order)->toBe(2);
	expect($items[2]->sort_order)->toBe(1);
});

it('prevents duplicate project placement', function () {
	asAdmin();
	$project = Project::factory()->create();

	$this->postJson('/api/dashboard/landing', ['project_id' => $project->id, 'column' => 1])
		->assertCreated();

	$this->postJson('/api/dashboard/landing', ['project_id' => $project->id, 'column' => 2])
		->assertUnprocessable()
		->assertJsonValidationErrors('project_id');
});

it('validates column must be 1, 2 or 3', function () {
	asAdmin();
	$project = Project::factory()->create();

	$this->postJson('/api/dashboard/landing', ['project_id' => $project->id, 'column' => 4])
		->assertUnprocessable()
		->assertJsonValidationErrors('column');
});

it('deletes a landing item', function () {
	asAdmin();
	$item = LandingItem::factory()->create();

	$this->deleteJson("/api/dashboard/landing/{$item->uuid}")
		->assertNoContent();

	expect(LandingItem::count())->toBe(0);
});

it('reorders landing items within and across columns', function () {
	asAdmin();
	$a = LandingItem::factory()->create(['column' => 1, 'sort_order' => 0]);
	$b = LandingItem::factory()->create(['column' => 1, 'sort_order' => 1]);

	$this->patchJson('/api/dashboard/landing/reorder', [
		'items' => [
			['uuid' => $a->uuid, 'column' => 2, 'sort_order' => 0],
			['uuid' => $b->uuid, 'column' => 1, 'sort_order' => 0],
		],
	])->assertNoContent();

	expect($a->fresh()->column)->toBe(2);
	expect($a->fresh()->sort_order)->toBe(0);
	expect($b->fresh()->column)->toBe(1);
	expect($b->fresh()->sort_order)->toBe(0);
});

it('forbids viewer from storing a landing item', function () {
	asViewer();
	$project = Project::factory()->create();

	$this->postJson('/api/dashboard/landing', ['project_id' => $project->id, 'column' => 1])
		->assertForbidden();
});

it('allows editor to delete a landing item', function () {
	asEditor();
	$item = LandingItem::factory()->create();

	$this->deleteJson("/api/dashboard/landing/{$item->uuid}")
		->assertNoContent();
});
