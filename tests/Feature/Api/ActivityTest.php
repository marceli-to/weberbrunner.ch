<?php

use Spatie\Activitylog\Models\Activity;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/activity')->assertUnauthorized();
});

it('returns paginated activity log', function () {
	asAdmin();
	$this->getJson('/api/dashboard/activity')
		->assertOk()
		->assertJsonStructure(['data', 'links']);
});

it('filters by causer_id', function () {
	$admin = asAdmin();
	// Create some activity by creating a model
	\App\Models\Location::factory()->create();
	$this->getJson("/api/dashboard/activity?causer_id={$admin->id}")
		->assertOk();
});

it('filters by date range', function () {
	asAdmin();
	$this->getJson('/api/dashboard/activity?from=2026-01-01&to=2026-12-31')
		->assertOk();
});
