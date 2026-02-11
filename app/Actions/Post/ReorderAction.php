<?php

namespace App\Actions\Post;

use App\Models\Post;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Post::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}
