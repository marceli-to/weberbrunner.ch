<?php

use App\Models\Category;
use App\Models\Project;
use App\Models\Status;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/projects')->assertUnauthorized();
});

it('lists projects', function () {
	asAdmin();
	Project::factory()->count(3)->create();
	$this->getJson('/api/dashboard/projects')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a project', function () {
	asAdmin();
	$this->postJson('/api/dashboard/projects', ['title' => 'New Project', 'number' => '101'])
		->assertCreated()
		->assertJsonPath('data.title', 'New Project')
		->assertJsonPath('data.slug', 'new-project');
});

it('creates a project with categories and statuses', function () {
	asAdmin();
	$category = Category::factory()->create();
	$status = Status::factory()->create();
	$response = $this->postJson('/api/dashboard/projects', [
		'title' => 'Full Project',
		'number' => '102',
		'categories' => [$category->id],
		'statuses' => [$status->id],
	]);
	$response->assertCreated();
	$project = Project::first();
	expect($project->categories)->toHaveCount(1);
	expect($project->statuses)->toHaveCount(1);
});

it('validates title is required', function () {
	asAdmin();
	$this->postJson('/api/dashboard/projects', [])
		->assertUnprocessable()
		->assertJsonValidationErrors('title');
});

it('shows a project', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->getJson("/api/dashboard/projects/{$project->uuid}")
		->assertOk()
		->assertJsonPath('data.title', $project->title);
});

it('updates a project', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->putJson("/api/dashboard/projects/{$project->uuid}", ['title' => 'Updated', 'number' => $project->number])
		->assertOk()
		->assertJsonPath('data.title', 'Updated');
});

it('deletes a project', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->deleteJson("/api/dashboard/projects/{$project->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted project', function () {
	asAdmin();
	$project = Project::factory()->create();
	$project->delete();
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/restore")
		->assertOk();
	expect(Project::count())->toBe(1);
});

it('reorders projects', function () {
	asAdmin();
	$a = Project::factory()->create();
	$b = Project::factory()->create();
	$this->patchJson('/api/dashboard/projects/reorder', [
		'items' => [
			['uuid' => $a->uuid, 'sort_order' => 2],
			['uuid' => $b->uuid, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});

it('updates meta description', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/meta-description", [
		'meta_description' => 'A new description',
	])->assertNoContent();
	expect($project->fresh()->meta_description)->toBe('A new description');
});

it('clears meta description with null', function () {
	asAdmin();
	$project = Project::factory()->create(['meta_description' => 'Old text']);
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/meta-description", [
		'meta_description' => null,
	])->assertNoContent();
	expect($project->fresh()->meta_description)->toBeNull();
});

it('syncs categories', function () {
	asAdmin();
	$project = Project::factory()->create();
	$categories = Category::factory()->count(2)->create();
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/categories", [
		'categories' => $categories->pluck('id')->all(),
	])->assertNoContent();
	expect($project->fresh()->categories)->toHaveCount(2);
});

it('replaces categories on re-sync', function () {
	asAdmin();
	$project = Project::factory()->create();
	$old = Category::factory()->create();
	$new = Category::factory()->create();
	$project->categories()->attach($old);
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/categories", [
		'categories' => [$new->id],
	])->assertNoContent();
	$fresh = $project->fresh()->categories;
	expect($fresh)->toHaveCount(1);
	expect($fresh->first()->id)->toBe($new->id);
});

it('syncs statuses', function () {
	asAdmin();
	$project = Project::factory()->create();
	$statuses = Status::factory()->count(2)->create();
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/statuses", [
		'statuses' => $statuses->pluck('id')->all(),
	])->assertNoContent();
	expect($project->fresh()->statuses)->toHaveCount(2);
});

it('replaces statuses on re-sync', function () {
	asAdmin();
	$project = Project::factory()->create();
	$old = Status::factory()->create();
	$new = Status::factory()->create();
	$project->statuses()->attach($old);
	$this->patchJson("/api/dashboard/projects/{$project->uuid}/statuses", [
		'statuses' => [$new->id],
	])->assertNoContent();
	$fresh = $project->fresh()->statuses;
	expect($fresh)->toHaveCount(1);
	expect($fresh->first()->id)->toBe($new->id);
});
