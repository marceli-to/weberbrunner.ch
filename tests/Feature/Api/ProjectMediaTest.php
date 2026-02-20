<?php

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

it('requires authentication', function () {
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/media", [])
		->assertUnauthorized();
});

it('attaches media to a project', function () {
	asAdmin();
	Storage::fake('public');
	Storage::disk('public')->put('temp/test.jpg', 'fake-image');
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/media", [
		'media' => [
			[
				'uuid' => Str::uuid()->toString(),
				'file' => 'test.jpg',
				'original_name' => 'test.jpg',
				'mime_type' => 'image/jpeg',
				'size' => 12345,
				'width' => 1920,
				'height' => 1080,
			],
		],
	])
		->assertOk();
	expect($project->media()->count())->toBe(1);
});

it('attaches multiple media at once', function () {
	asAdmin();
	Storage::fake('public');
	Storage::disk('public')->put('temp/a.jpg', 'fake-image');
	Storage::disk('public')->put('temp/b.jpg', 'fake-image');
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/media", [
		'media' => [
			[
				'uuid' => Str::uuid()->toString(),
				'file' => 'a.jpg',
				'original_name' => 'a.jpg',
				'mime_type' => 'image/jpeg',
				'size' => 1000,
				'width' => 800,
				'height' => 600,
			],
			[
				'uuid' => Str::uuid()->toString(),
				'file' => 'b.jpg',
				'original_name' => 'b.jpg',
				'mime_type' => 'image/jpeg',
				'size' => 2000,
				'width' => 1024,
				'height' => 768,
			],
		],
	])
		->assertOk();
	expect($project->media()->count())->toBe(2);
});

it('validates media array is required', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/media", [])
		->assertUnprocessable()
		->assertJsonValidationErrors('media');
});

it('validates media fields are required', function () {
	asAdmin();
	$project = Project::factory()->create();
	$this->postJson("/api/dashboard/projects/{$project->uuid}/media", [
		'media' => [['uuid' => Str::uuid()->toString()]],
	])
		->assertUnprocessable();
});
