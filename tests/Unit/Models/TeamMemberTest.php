<?php

use App\Models\Location;
use App\Models\TeamMember;
use App\Models\TeamMemberBio;

it('has many bios', function () {
	$member = TeamMember::factory()->create();
	TeamMemberBio::factory()->count(2)->create(['team_member_id' => $member->id]);
	expect($member->bios)->toHaveCount(2);
});

it('belongs to a location', function () {
	$location = Location::factory()->create();
	$member = TeamMember::factory()->create(['location_id' => $location->id]);
	expect($member->location->id)->toBe($location->id);
});

it('soft deletes', function () {
	$member = TeamMember::factory()->create();
	$member->delete();
	expect(TeamMember::count())->toBe(0);
	expect(TeamMember::withTrashed()->count())->toBe(1);
});
