<?php

use App\Models\Project;
use App\Models\ProjectAttribute;

it('requires authentication', function () {
	$project = Project::factory()->create();
	$this->getJson("/api/dashboard/projects/{$project->uuid}/attributes")->assertUnauthorized();
});

it('lists attributes for a project', function () {
	asAdmin();
	$project = Project::factory()->create();
	ProjectAttribute::factory()->count(3)->create(['project_id' => $project->id]);
	$this->getJson("/api/dashboard/projects/{$project->uuid}/attributes")
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates an attribute', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/attributes", [
		'label' => 'Area',
		'value' => '200m2',
	])
		->assertCreated()
		->assertJsonPath('data.label', 'Area');
});

it('validates required fields', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/attributes", [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['label', 'value']);
});

it('updates an attribute', function () {
	asAdmin();
	$project = Project::factory()->create();
	$attribute = ProjectAttribute::factory()->create(['project_id' => $project->id]);
	$this->putJson("/api/dashboard/projects/{$project->uuid}/attributes/{$attribute->uuid}", [
		'label' => 'Updated',
		'value' => 'New Value',
	])
		->assertOk()
		->assertJsonPath('data.label', 'Updated');
});

it('deletes an attribute', function () {
	asAdmin();
	$project = Project::factory()->create();
	$attribute = ProjectAttribute::factory()->create(['project_id' => $project->id]);
	$this->deleteJson("/api/dashboard/projects/{$project->uuid}/attributes/{$attribute->uuid}")
		->assertNoContent();
	expect(ProjectAttribute::count())->toBe(0);
});

it('reorders attributes', function () {
	asAdmin();
	$project = Project::factory()->create();
	$a = ProjectAttribute::factory()->create(['project_id' => $project->id]);
	$b = ProjectAttribute::factory()->create(['project_id' => $project->id]);
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/attributes/reorder", [
		'items' => [
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
