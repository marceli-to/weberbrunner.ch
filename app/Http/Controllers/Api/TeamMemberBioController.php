<?php

namespace App\Http\Controllers\Api;

use App\Actions\TeamMemberBio\DeleteAction as DeleteTeamMemberBioAction;
use App\Actions\TeamMemberBio\ReorderAction as ReorderTeamMemberBioAction;
use App\Actions\TeamMemberBio\StoreAction as StoreTeamMemberBioAction;
use App\Actions\TeamMemberBio\UpdateAction as UpdateTeamMemberBioAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamMemberBio\StoreTeamMemberBioRequest;
use App\Http\Requests\TeamMemberBio\UpdateTeamMemberBioRequest;
use App\Http\Resources\TeamMemberBioResource;
use App\Models\TeamMember;
use App\Models\TeamMemberBio;

class TeamMemberBioController extends Controller
{
	public function index(TeamMember $teamMember)
	{
		return TeamMemberBioResource::collection(
			$teamMember->bios()->orderBy('sort_order')->get()
		);
	}

	public function store(StoreTeamMemberBioRequest $request, TeamMember $teamMember)
	{
		$bio = (new StoreTeamMemberBioAction)->execute($teamMember, $request->validated());

		return new TeamMemberBioResource($bio);
	}

	public function update(UpdateTeamMemberBioRequest $request, TeamMember $teamMember, TeamMemberBio $bio)
	{
		$bio = (new UpdateTeamMemberBioAction)->execute($bio, $request->validated());

		return new TeamMemberBioResource($bio);
	}

	public function destroy(TeamMember $teamMember, TeamMemberBio $bio)
	{
		(new DeleteTeamMemberBioAction)->execute($bio);

		return response()->json(null, 204);
	}

	public function reorder(TeamMember $teamMember)
	{
		(new ReorderTeamMemberBioAction)->execute(request('items'));

		return response()->json(null, 204);
	}
}
