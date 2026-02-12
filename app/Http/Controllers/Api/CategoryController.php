<?php

namespace App\Http\Controllers\Api;

use App\Actions\Category\DeleteAction as DeleteCategoryAction;
use App\Actions\Category\ReorderAction as ReorderCategoryAction;
use App\Actions\Category\StoreAction as StoreCategoryAction;
use App\Actions\Category\UpdateAction as UpdateCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\ReorderCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Category::class);

		$categories = Category::orderBy('sort_order')->get();

		return CategoryResource::collection($categories);
	}

	public function store(StoreCategoryRequest $request)
	{
		$this->authorize('create', Category::class);

		$category = (new StoreCategoryAction)->execute($request->validated());

		return new CategoryResource($category);
	}

	public function show(Category $category)
	{
		$this->authorize('view', $category);

		return new CategoryResource($category);
	}

	public function update(UpdateCategoryRequest $request, Category $category)
	{
		$this->authorize('update', $category);

		$category = (new UpdateCategoryAction)->execute($category, $request->validated());

		return new CategoryResource($category);
	}

	public function destroy(Category $category)
	{
		$this->authorize('delete', $category);

		(new DeleteCategoryAction)->execute($category);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$category = Category::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $category);

		$category->restore();

		return new CategoryResource($category);
	}

	public function reorder(ReorderCategoryRequest $request)
	{
		$this->authorize('create', Category::class);

		(new ReorderCategoryAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
