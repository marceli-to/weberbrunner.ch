<?php

use App\Actions\Location\ReorderAction;
use App\Models\Location;

it('reorders locations by id and sort_order', function () {
	$a = Location::factory()->create();
	$b = Location::factory()->create();
	(new ReorderAction)->execute([
		['id' => $a->id, 'sort_order' => 2],
		['id' => $b->id, 'sort_order' => 1],
	]);
	expect($a->fresh()->sort_order)->toBe(2);
	expect($b->fresh()->sort_order)->toBe(1);
});
