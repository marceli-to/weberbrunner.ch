<?php

namespace App\Actions\Status;

use App\Models\Status;
use Illuminate\Support\Str;

class StoreAction
{
	public function execute(array $data): Status
	{
		$data['slug'] = Str::slug($data['title']);

		return Status::create($data);
	}
}
