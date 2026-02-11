<?php

namespace App\Actions\TeamMember;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Models\TeamMember;
use Illuminate\Support\Str;

class StoreAction
{
	public function execute(array $data): TeamMember
	{
		$media = $data['media'] ?? [];
		unset($data['media']);

		$data['slug'] = Str::slug($data['firstname'] . ' ' . $data['name']);

		$teamMember = TeamMember::create($data);

		if (!empty($media)) {
			(new AttachMediaAction)->execute($media, $teamMember);
		}

		return $teamMember;
	}
}
