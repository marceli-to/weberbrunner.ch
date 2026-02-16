<?php

use App\Models\Category;
use App\Models\Location;
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

it('filters projects by search', function () {
	asAdmin();
	Project::factory()->create(['title' => 'Alpine House']);
	Project::factory()->create(['title' => 'City Tower']);
	$this->getJson('/api/dashboard/projects?search=Alpine')
		->assertOk()
		->assertJsonCount(1, 'data');
});

it('filters projects by category', function () {
	asAdmin();
	$category = Category::factory()->create();
	$project = Project::factory()->create();
	$project->categories()->attach($category);
	Project::factory()->create();
	$this->getJson("/api/dashboard/projects?category={$category->id}")
		->assertOk()
		->assertJsonCount(1, 'data');
});

it('filters projects by status', function () {
	asAdmin();
	$status = Status::factory()->create();
	$project = Project::factory()->create();
	$project->statuses()->attach($status);
	Project::factory()->create();
	$this->getJson("/api/dashboard/projects?status={$status->id}")
		->assertOk()
		->assertJsonCount(1, 'data');
});

it('filters projects by location', function () {
	asAdmin();
	$location = Location::factory()->create();
	Project::factory()->create(['location_id' => $location->id]);
	Project::factory()->create();
	$this->getJson("/api/dashboard/projects?location={$location->id}")
		->assertOk()
		->assertJsonCount(1, 'data');
});

it('filters projects by publish status', function () {
	asAdmin();
	Project::factory()->create(['publish' => true]);
	Project::factory()->create(['publish' => false]);
	$this->getJson('/api/dashboard/projects?publish=1')
		->assertOk()
		->assertJsonCount(1, 'data');
});

it('filters trashed projects', function () {
	asAdmin();
	$project = Project::factory()->create();
	$project->delete();
	Project::factory()->create();
	$this->getJson('/api/dashboard/projects?trashed=1')
		->assertOk()
		->assertJsonCount(1, 'data');
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
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
