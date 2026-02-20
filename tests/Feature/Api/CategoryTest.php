<?php

use App\Models\Category;

it('requires authentication', function () {
	$this->getJson('/api/dashboard/categories')->assertUnauthorized();
});

it('lists categories', function () {
	asAdmin();
	Category::factory()->count(3)->create();
	$this->getJson('/api/dashboard/categories')
		->assertOk()
		->assertJsonCount(3, 'data');
});

it('creates a category', function () {
	asAdmin();
	$this->postJson('/api/dashboard/categories', ['title' => 'Architecture'])
		->assertCreated()
		->assertJsonPath('data.title', 'Architecture')
		->assertJsonPath('data.slug', 'architecture');
});

it('validates title is required', function () {
	asAdmin();
	$this->postJson('/api/dashboard/categories', [])
		->assertUnprocessable()
		->assertJsonValidationErrors('title');
});

it('shows a category', function () {
	asAdmin();
	$category = Category::factory()->create();
	$this->getJson("/api/dashboard/categories/{$category->uuid}")
		->assertOk()
		->assertJsonPath('data.title', $category->title);
});

it('updates a category', function () {
	asAdmin();
	$category = Category::factory()->create();
	$this->putJson("/api/dashboard/categories/{$category->uuid}", ['title' => 'Design'])
		->assertOk()
		->assertJsonPath('data.title', 'Design');
});

it('deletes a category', function () {
	asAdmin();
	$category = Category::factory()->create();
	$this->deleteJson("/api/dashboard/categories/{$category->uuid}")
		->assertNoContent();
	expect(Category::count())->toBe(0);
});

it('restores a soft-deleted category', function () {
	asAdmin();
	$category = Category::factory()->create();
	$category->delete();
	$this->patchJson("/api/dashboard/categories/{$category->uuid}/restore")
		->assertOk();
	expect(Category::count())->toBe(1);
});

it('reorders categories', function () {
	asAdmin();
	$a = Category::factory()->create();
	$b = Category::factory()->create();
	$this->patchJson('/api/dashboard/categories/reorder', [
		'items' => [
			['uuid' => $a->uuid, 'sort_order' => 2],
			['uuid' => $b->uuid, 'sort_order' => 1],
		],
	])->assertNoContent();
	expect($a->fresh()->sort_order)->toBe(2);
});
