<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Str;

class UpdateAction
{
	public function execute(Category $category, array $data): Category
	{
		$data['slug'] = Str::slug($data['title']);

		$category->update($data);

		return $category;
	}
}
