<?php

namespace App\Actions\Publication;

use App\Models\Publication;
use Illuminate\Support\Facades\DB;

class UpdateAction
{
	public function execute(Publication $publication, array $data): Publication
	{
		return DB::transaction(function () use ($publication, $data): Publication {
			$publication->update($data);
			return $publication;
		});
	}
}
