<?php

namespace App\Http\Controllers\Api;

use App\Actions\TeamMember\DeleteAction as DeleteTeamMemberAction;
use App\Actions\TeamMember\ReorderAction as ReorderTeamMemberAction;
use App\Actions\TeamMember\StoreAction as StoreTeamMemberAction;
use App\Actions\TeamMember\UpdateAction as UpdateTeamMemberAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamMember\StoreTeamMemberRequest;
use App\Http\Requests\TeamMember\UpdateTeamMemberRequest;
use App\Http\Resources\TeamMemberResource;
use App\Models\TeamMember;

class TeamMemberController extends Controller
{
	public function index()
	{
		$members = TeamMember::with(['bios', 'media', 'location'])
			->orderBy('sort_order')
			->get();

		return TeamMemberResource::collection($members);
	}

	public function store(StoreTeamMemberRequest $request)
	{
		$member = (new StoreTeamMemberAction)->execute($request->validated());

		return new TeamMemberResource($member->load(['bios', 'media', 'location']));
	}

	public function show(TeamMember $teamMember)
	{
		$teamMember->load(['bios', 'media', 'location']);

		return new TeamMemberResource($teamMember);
	}

	public function update(UpdateTeamMemberRequest $request, TeamMember $teamMember)
	{
		$member = (new UpdateTeamMemberAction)->execute($teamMember, $request->validated());

		return new TeamMemberResource($member->load(['bios', 'media', 'location']));
	}

	public function destroy(TeamMember $teamMember)
	{
		(new DeleteTeamMemberAction)->execute($teamMember);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$member = TeamMember::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$member->restore();

		return new TeamMemberResource($member->load(['bios', 'media', 'location']));
	}

	public function reorder()
	{
		(new ReorderTeamMemberAction)->execute(request('items'));

		return response()->json(null, 204);
	}
}
