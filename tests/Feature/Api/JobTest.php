<?php

use App\Models\Job;
use App\Models\Location;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/jobs')->assertUnauthorized();
});

it('lists jobs', function () {
	asAdmin();
	Job::factory()->count(3)->create();
	$this->getJson('/api/dashboard/jobs')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a job', function () {
	asAdmin();
	$this->postJson('/api/dashboard/jobs', [
		'title' => 'Architect',
		'description' => 'Job description here',
	])
		->assertCreated()
		->assertJsonPath('data.title', 'Architect');
});

it('validates required fields', function () {
	asAdmin();
	$this->postJson('/api/dashboard/jobs', [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['title', 'description']);
});

it('creates a job with location', function () {
	asAdmin();
	$location = Location::factory()->create();
	$this->postJson('/api/dashboard/jobs', [
		'title' => 'Engineer',
		'description' => 'Build things',
		'location_id' => $location->id,
	])
		->assertCreated();
});

it('shows a job', function () {
	asAdmin();
	$job = Job::factory()->create();
	$this->getJson("/api/dashboard/jobs/{$job->uuid}")
		->assertOk()
		->assertJsonPath('data.title', $job->title);
});

it('updates a job', function () {
	asAdmin();
	$job = Job::factory()->create();
	$this->putJson("/api/dashboard/jobs/{$job->uuid}", [
		'title' => 'Senior Architect',
		'description' => 'Updated description',
	])
		->assertOk()
		->assertJsonPath('data.title', 'Senior Architect');
});

it('deletes a job', function () {
	asAdmin();
	$job = Job::factory()->create();
	$this->deleteJson("/api/dashboard/jobs/{$job->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted job', function () {
	asAdmin();
	$job = Job::factory()->create();
	$job->delete();
	$this->patchJson("/api/dashboard/jobs/{$job->uuid}/restore")
		->assertOk();
	expect(Job::count())->toBe(1);
});

it('reorders jobs', function () {
	asAdmin();
	$a = Job::factory()->create();
	$b = Job::factory()->create();
	$this->patchJson('/api/dashboard/jobs/reorder', [
		'items' => [
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
