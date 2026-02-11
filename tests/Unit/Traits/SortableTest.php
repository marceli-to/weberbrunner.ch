<?php

use App\Models\Location;

it('auto-increments sort_order', function () {
	$a = Location::factory()->create();
	$b = Location::factory()->create();
	expect($b->sort_order)->toBe($a->sort_order + 1);
});

it('respects an explicit sort_order', function () {
	$location = Location::factory()->create(['sort_order' => 42]);
	expect($location->sort_order)->toBe(42);
});
