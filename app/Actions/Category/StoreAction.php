<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Str;

class StoreAction
{
	public function execute(array $data): Category
	{
		$data['slug'] = Str::slug($data['title']);

		return Category::create($data);
	}
}
