<?php

namespace App\Actions\Project;

use App\Models\Project;
use Illuminate\Support\Str;

class StoreAction
{
	public function execute(array $data): Project
	{
		$data['slug'] = Str::slug(trim($data['title'].' '.($data['city'] ?? '')));
		return Project::create($data);
	}
}
