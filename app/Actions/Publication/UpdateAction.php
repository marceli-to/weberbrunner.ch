<?php

namespace App\Actions\Publication;

use App\Models\Publication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateAction
{
	public function execute(Publication $publication, array $data): Publication
	{
		return DB::transaction(function () use ($publication, $data): Publication {
			if (isset($data['title'])) {
				$data['slug'] = Str::slug($data['title']);
			}
			$publication->update($data);
			return $publication;
		});
	}
}
