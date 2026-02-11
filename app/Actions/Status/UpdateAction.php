<?php

namespace App\Actions\Status;

use App\Models\Status;
use Illuminate\Support\Str;

class UpdateAction
{
	public function execute(Status $status, array $data): Status
	{
		$data['slug'] = Str::slug($data['title']);

		$status->update($data);

		return $status;
	}
}
