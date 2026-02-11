<?php

use App\Actions\Location\DeleteAction;
use App\Models\Location;
use App\Models\Project;

it('soft deletes a location without related records', function () {
	$location = Location::factory()->create();
	(new DeleteAction)->execute($location);
	expect(Location::count())->toBe(0);
	expect(Location::withTrashed()->count())->toBe(1);
});

it('throws when location has related projects', function () {
	$location = Location::factory()->create();
	Project::factory()->create(['location_id' => $location->id]);
	(new DeleteAction)->execute($location);
})->throws(\Exception::class, 'Cannot delete location with related records.');
