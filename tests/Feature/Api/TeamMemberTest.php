<?php

use App\Models\Location;
use App\Models\TeamMember;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/team')->assertUnauthorized();
});

it('lists team members', function () {
	asAdmin();
	TeamMember::factory()->count(3)->create();
	$this->getJson('/api/dashboard/team')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a team member', function () {
	asAdmin();
	$this->postJson('/api/dashboard/team', [
		'firstname' => 'Max',
		'name' => 'Muster',
		'email' => 'max@example.com',
	])
		->assertCreated()
		->assertJsonPath('data.firstname', 'Max')
		->assertJsonPath('data.slug', 'max-muster');
});

it('validates required fields', function () {
	asAdmin();
	$this->postJson('/api/dashboard/team', [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['firstname', 'name', 'email']);
});

it('creates a team member with location', function () {
	asAdmin();
	$location = Location::factory()->create();
	$this->postJson('/api/dashboard/team', [
		'firstname' => 'Anna',
		'name' => 'Schmidt',
		'email' => 'anna@example.com',
		'location_id' => $location->id,
	])
		->assertCreated();
});

it('shows a team member', function () {
	asAdmin();
	$member = TeamMember::factory()->create();
	$this->getJson("/api/dashboard/team/{$member->uuid}")
		->assertOk();
});

it('updates a team member', function () {
	asAdmin();
	$member = TeamMember::factory()->create();
	$this->putJson("/api/dashboard/team/{$member->uuid}", [
		'firstname' => 'Updated',
		'name' => 'Name',
		'email' => 'updated@example.com',
	])
		->assertOk()
		->assertJsonPath('data.firstname', 'Updated');
});

it('deletes a team member', function () {
	asAdmin();
	$member = TeamMember::factory()->create();
	$this->deleteJson("/api/dashboard/team/{$member->uuid}")
		->assertNoContent();
});

it('restores a soft-deleted team member', function () {
	asAdmin();
	$member = TeamMember::factory()->create();
	$member->delete();
	$this->patchJson("/api/dashboard/team/{$member->uuid}/restore")
		->assertOk();
	expect(TeamMember::count())->toBe(1);
});

it('reorders team members', function () {
	asAdmin();
	$a = TeamMember::factory()->create();
	$b = TeamMember::factory()->create();
	$this->patchJson('/api/dashboard/team/reorder', [
		'items' => [
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
