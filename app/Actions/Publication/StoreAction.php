<?php

namespace App\Actions\Publication;

use App\Models\Publication;
use Illuminate\Support\Facades\DB;

class StoreAction
{
	public function execute(array $data): Publication
	{
		return DB::transaction(function () use ($data): Publication {
			return Publication::create($data);
		});
	}
}
