<?php

use App\Models\Category;
use App\Models\Location;
use App\Models\Project;
use App\Models\Status;

it('belongs to a location', function () {
	$location = Location::factory()->create();
	$project = Project::factory()->create(['location_id' => $location->id]);
	expect($project->location->id)->toBe($location->id);
});

it('belongs to many categories', function () {
	$project = Project::factory()->create();
	$categories = Category::factory()->count(2)->create();
	$project->categories()->attach($categories);
	expect($project->categories)->toHaveCount(2);
});

it('belongs to many statuses', function () {
	$project = Project::factory()->create();
	$statuses = Status::factory()->count(2)->create();
	$project->statuses()->attach($statuses);
	expect($project->statuses)->toHaveCount(2);
});

it('scopes published projects', function () {
	Project::factory()->create(['publish' => true]);
	Project::factory()->create(['publish' => false]);
	expect(Project::published()->count())->toBe(1);
});

it('soft deletes', function () {
	$project = Project::factory()->create();
	$project->delete();
	expect(Project::count())->toBe(0);
	expect(Project::withTrashed()->count())->toBe(1);
});
