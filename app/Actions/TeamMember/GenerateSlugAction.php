<?php

namespace App\Actions\TeamMember;

use App\Models\TeamMember;
use Illuminate\Support\Str;

class GenerateSlugAction
{
	public function execute(string $firstname, string $name, ?int $ignoreId = null): string
	{
		$base = Str::slug($firstname . ' ' . $name, '-', 'de');
		$slug = $base;
		$suffix = 2;

		while ($this->exists($slug, $ignoreId)) {
			$slug = $base . '-' . $suffix++;
		}

		return $slug;
	}

	private function exists(string $slug, ?int $ignoreId): bool
	{
		return TeamMember::withTrashed()
			->where('slug', $slug)
			->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
			->exists();
	}
}
