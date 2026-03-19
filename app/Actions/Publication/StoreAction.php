<?php

namespace App\Actions\Publication;

use App\Models\Publication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreAction
{
	public function execute(array $data): Publication
	{
		return DB::transaction(function () use ($data): Publication {
			$data['slug'] = Str::slug($data['title']);
			return Publication::create($data);
		});
	}
}
