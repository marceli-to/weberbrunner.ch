<?php

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Database\Eloquent\Collection;

class ListAction
{
	public function execute(?string $search = null): Collection
	{
		return Media::query()
			->when($search, fn ($q) => $q->where('original_name', 'like', "%{$search}%"))
			->orderByDesc('created_at')
			->get();
	}
}
