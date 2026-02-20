<?php

use App\Models\Project;
use App\Models\ProjectLink;

it('requires authentication', function () {
	$project = Project::factory()->create();
	$this->getJson("/api/dashboard/projects/{$project->uuid}/links")->assertUnauthorized();
});

it('lists links for a project', function () {
	asAdmin();
	$project = Project::factory()->create();
	ProjectLink::factory()->count(3)->create(['project_id' => $project->id]);
	$this->getJson("/api/dashboard/projects/{$project->uuid}/links")
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a link', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/links", [
		'url' => 'https://example.com',
	])
		->assertCreated()
		->assertJsonPath('data.url', 'https://example.com');
});

it('validates url is required', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/links", [])
		->assertUnprocessable()
		->assertJsonValidationErrors('url');
});

it('updates a link', function () {
	asAdmin();
	$project = Project::factory()->create();
	$link = ProjectLink::factory()->create(['project_id' => $project->id]);
	$this->putJson("/api/dashboard/projects/{$project->uuid}/links/{$link->uuid}", [
		'url' => 'https://updated.com',
	])
		->assertOk()
		->assertJsonPath('data.url', 'https://updated.com');
});

it('deletes a link', function () {
	asAdmin();
	$project = Project::factory()->create();
	$link = ProjectLink::factory()->create(['project_id' => $project->id]);
	$this->deleteJson("/api/dashboard/projects/{$project->uuid}/links/{$link->uuid}")
		->assertNoContent();
	expect(ProjectLink::count())->toBe(0);
});

it('reorders links', function () {
	asAdmin();
	$project = Project::factory()->create();
	$a = ProjectLink::factory()->create(['project_id' => $project->id]);
	$b = ProjectLink::factory()->create(['project_id' => $project->id]);
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/links/reorder", [
		'items' => [
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
