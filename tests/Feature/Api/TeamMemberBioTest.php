<?php

use App\Models\TeamMember;
use App\Models\TeamMemberBio;

it('requires authentication', function () {
	$member = TeamMember::factory()->create();
	$this->getJson("/api/dashboard/team/{$member->uuid}/cv")->assertUnauthorized();
});

it('lists bios for a team member', function () {
	asAdmin();
	$member = TeamMember::factory()->create();
	TeamMemberBio::factory()->count(3)->create(['team_member_id' => $member->id]);
	$this->getJson("/api/dashboard/team/{$member->uuid}/cv")
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a bio', function () {
	asAdmin();
	$member = TeamMember::factory()->create();
	$this->postJson("/api/dashboard/team/{$member->uuid}/cv", [
		'period' => '2020 - 2025',
		'description' => 'Worked at company X',
	])
		->assertCreated()
		->assertJsonPath('data.period', '2020 - 2025');
});

it('validates required fields', function () {
	asAdmin();
	$member = TeamMember::factory()->create();
	$this->postJson("/api/dashboard/team/{$member->uuid}/cv", [])
		->assertUnprocessable()
		->assertJsonValidationErrors(['period', 'description']);
});

it('updates a bio', function () {
	asAdmin();
	$member = TeamMember::factory()->create();
	$bio = TeamMemberBio::factory()->create(['team_member_id' => $member->id]);
	$this->putJson("/api/dashboard/team/{$member->uuid}/cv/{$bio->uuid}", [
		'period' => '2018 - 2020',
		'description' => 'Updated bio',
	])
		->assertOk()
		->assertJsonPath('data.period', '2018 - 2020');
});

it('deletes a bio', function () {
	asAdmin();
	$member = TeamMember::factory()->create();
	$bio = TeamMemberBio::factory()->create(['team_member_id' => $member->id]);
	$this->deleteJson("/api/dashboard/team/{$member->uuid}/cv/{$bio->uuid}")
		->assertNoContent();
	expect(TeamMemberBio::count())->toBe(0);
});

it('reorders bios', function () {
	asAdmin();
	$member = TeamMember::factory()->create();
	$a = TeamMemberBio::factory()->create(['team_member_id' => $member->id]);
	$b = TeamMemberBio::factory()->create(['team_member_id' => $member->id]);
	$this->patchJson("/api/dashboard/team/{$member->uuid}/cv/reorder", [
		'items' => [
			['id' => $a->id, 'sort_order' => 2],
			['id' => $b->id, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
