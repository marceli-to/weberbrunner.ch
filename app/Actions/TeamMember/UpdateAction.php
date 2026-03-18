<?php

namespace App\Actions\TeamMember;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Models\TeamMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateAction
{
	public function execute(TeamMember $teamMember, array $data): TeamMember
	{
		$media = $data['media'] ?? [];
		unset($data['media']);

		$data['slug'] = Str::slug($data['firstname'] . ' ' . $data['name']);

		return DB::transaction(function () use ($teamMember, $data, $media): TeamMember {
			$teamMember->update($data);

			if (!empty($media)) {
				(new AttachMediaAction)->execute($media, $teamMember);
			}

			return $teamMember;
		});
	}
}
