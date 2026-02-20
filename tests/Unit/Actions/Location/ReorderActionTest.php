<?php

use App\Actions\Location\ReorderAction;
use App\Models\Location;

it('reorders locations by uuid and sort_order', function () {
	$a = Location::factory()->create();
	$b = Location::factory()->create();
	(new ReorderAction)->execute([
		['uuid' => $a->uuid, 'sort_order' => 2],
		['uuid' => $b->uuid, 'sort_order' => 1],
	]);
	expect($a->fresh()->sort_order)->toBe(2);
	expect($b->fresh()->sort_order)->toBe(1);
});
