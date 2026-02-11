<?php

use App\Actions\Location\StoreAction;

it('creates a location with auto-slug', function () {
	$location = (new StoreAction)->execute(['title' => 'New York City']);
	expect($location->title)->toBe('New York City');
	expect($location->slug)->toBe('new-york-city');
	expect($location->exists)->toBeTrue();
});
