<?php

namespace App\Http\Controllers\Api;

use App\Actions\Landing\FindAction as FindLandingAction;
use App\Actions\Landing\UpdateAction as UpdateLandingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Landing\UpdateLandingRequest;
use App\Http\Resources\LandingResource;
use App\Models\Landing;

class LandingTextController extends Controller
{
	public function show()
	{
		$this->authorize('viewAny', Landing::class);

		$landing = (new FindLandingAction)->execute();

		return new LandingResource($landing);
	}

	public function update(UpdateLandingRequest $request)
	{
		$landing = (new FindLandingAction)->execute();

		(new UpdateLandingAction)->execute($landing, $request->validated());

		return new LandingResource($landing);
	}
}
