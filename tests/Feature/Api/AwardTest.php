<?php

use App\Models\Award;
use App\Models\Project;
use App\Models\Section;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/awards')->assertUnauthorized();
});

it('lists awards', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'award']);
	Award::factory()->count(3)->create(['section_id' => $section->id]);
	$this->getJson('/api/dashboard/awards')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates an award', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'award']);
	$this->postJson('/api/dashboard/awards', [
		'title' => 'Best Design',
		'section_id' => $section->id,
	])
		->assertCreated()
		->assertJsonPath('data.title', 'Best Design');
});

it('validates required fields', function () {
	asAdmin();
	$this->postJson('/api/dashboard/awards', [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['title', 'section_id']);
});

it('creates an award with project', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'award']);
	$project = Project::factory()->create();
	$this->postJson('/api/dashboard/awards', [
		'title' => 'Award',
		'section_id' => $section->id,
		'project_id' => $project->id,
	])
		->assertCreated();
});

it('shows an award', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'award']);
	$award = Award::factory()->create(['section_id' => $section->id]);
	$this->getJson("/api/dashboard/awards/{$award->uuid}")
		->assertOk();
});

it('updates an award', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'award']);
	$award = Award::factory()->create(['section_id' => $section->id]);
	$this->putJson("/api/dashboard/awards/{$award->uuid}", [
		'title' => 'Updated Award',
		'section_id' => $section->id,
	])
		->assertOk()
		->assertJsonPath('data.title', 'Updated Award');
});

it('deletes an award', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'award']);
	$award = Award::factory()->create(['section_id' => $section->id]);
	$this->deleteJson("/api/dashboard/awards/{$award->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted award', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'award']);
	$award = Award::factory()->create(['section_id' => $section->id]);
	$award->delete();
	$this->patchJson("/api/dashboard/awards/{$award->uuid}/restore")
		->assertOk();
	expect(Award::count())->toBe(1);
});

it('reorders awards', function () {
	asAdmin();
	$section = Section::factory()->create(['type' => 'award']);
	$a = Award::factory()->create(['section_id' => $section->id]);
	$b = Award::factory()->create(['section_id' => $section->id]);
	$this->patchJson('/api/dashboard/awards/reorder', [
		'items' => [
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
