<?php

use App\Models\Job;
use App\Models\Location;
use App\Models\Project;
use App\Models\TeamMember;

it('has many projects', function () {
	$location = Location::factory()->create();
	Project::factory()->count(2)->create(['location_id' => $location->id]);
	expect($location->projects)->toHaveCount(2);
});

it('has many team members', function () {
	$location = Location::factory()->create();
	TeamMember::factory()->count(2)->create(['location_id' => $location->id]);
	expect($location->teamMembers)->toHaveCount(2);
});

it('has many jobs', function () {
	$location = Location::factory()->create();
	Job::factory()->count(2)->create(['location_id' => $location->id]);
	expect($location->jobs)->toHaveCount(2);
});

it('soft deletes', function () {
	$location = Location::factory()->create();
	$location->delete();
	expect(Location::count())->toBe(0);
	expect(Location::withTrashed()->count())->toBe(1);
});
