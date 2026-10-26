<?php

namespace App\Actions\TeamMember;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Models\TeamMember;
use Illuminate\Support\Facades\DB;

class UpdateAction
{
	public function execute(TeamMember $teamMember, array $data): TeamMember
	{
		$media = $data['media'] ?? [];
		unset($data['media']);

		$data['slug'] = (new GenerateSlugAction)->execute($data['firstname'], $data['name'], $teamMember->id);

		return DB::transaction(function () use ($teamMember, $data, $media): TeamMember {
			$teamMember->update($data);

			if (!empty($media)) {
				(new AttachMediaAction)->execute($media, $teamMember);
			}

			return $teamMember;
		});
	}
}
