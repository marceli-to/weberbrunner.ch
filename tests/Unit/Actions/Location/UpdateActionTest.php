<?php

use App\Actions\Location\UpdateAction;
use App\Models\Location;

it('updates a location with auto-slug', function () {
	$location = Location::factory()->create(['title' => 'Old Title']);
	$updated = (new UpdateAction)->execute($location, ['title' => 'San Francisco']);
	expect($updated->title)->toBe('San Francisco');
	expect($updated->slug)->toBe('san-francisco');
});
