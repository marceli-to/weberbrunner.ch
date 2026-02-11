<?php

use App\Models\User;

it('identifies admin role', function () {
	$user = User::factory()->create(['role' => 'admin']);
	expect($user->isAdmin())->toBeTrue();
	expect($user->isEditor())->toBeFalse();
	expect($user->isViewer())->toBeFalse();
});

it('identifies editor role', function () {
	$user = User::factory()->create(['role' => 'editor']);
	expect($user->isAdmin())->toBeFalse();
	expect($user->isEditor())->toBeTrue();
	expect($user->isViewer())->toBeFalse();
});

it('identifies viewer role', function () {
	$user = User::factory()->create(['role' => 'viewer']);
	expect($user->isAdmin())->toBeFalse();
	expect($user->isEditor())->toBeFalse();
	expect($user->isViewer())->toBeTrue();
});
