<?php

use App\Models\Location;
use Illuminate\Support\Str;

it('auto-generates a uuid on creation', function () {
	$location = Location::factory()->create();
	$location->refresh();
	expect($location->uuid)->not->toBeNull();
	expect(Str::isUuid($location->uuid))->toBeTrue();
});

it('does not overwrite an existing uuid', function () {
	$uuid = Str::uuid()->toString();
	$location = Location::factory()->create(['uuid' => $uuid]);
	expect($location->uuid)->toBe($uuid);
});

it('uses uuid as route key name', function () {
	$location = Location::factory()->create();
	expect($location->getRouteKeyName())->toBe('uuid');
});
