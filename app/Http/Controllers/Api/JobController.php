<?php

namespace App\Http\Controllers\Api;

use App\Actions\Job\DeleteAction as DeleteJobAction;
use App\Actions\Job\ReorderAction as ReorderJobAction;
use App\Actions\Job\StoreAction as StoreJobAction;
use App\Actions\Job\ToggleAction as ToggleJobAction;
use App\Actions\Job\UpdateAction as UpdateJobAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Job\StoreJobRequest;
use App\Http\Requests\Job\ReorderJobRequest;
use App\Http\Requests\Job\UpdateJobRequest;
use App\Http\Resources\JobResource;
use App\Http\Resources\LocationResource;
use App\Models\Job;
use App\Models\Location;

class JobController extends Controller
{
	public function index()
	{
		$this->authorize('viewAny', Job::class);

		$locations = Location::query()
			->orderBy('sort_order')
			->with(['jobs' => fn ($q) => $q->orderBy('sort_order')])
			->get();

		$grouped = $locations->map(fn ($location) => [
			'location' => new LocationResource($location),
			'jobs' => JobResource::collection($location->jobs),
		]);

		return response()->json(['data' => $grouped]);
	}

	public function store(StoreJobRequest $request)
	{
		$this->authorize('create', Job::class);

		$job = (new StoreJobAction)->execute($request->validated());

		return new JobResource($job->load('location'));
	}

	public function show(Job $job)
	{
		$this->authorize('view', $job);

		$job->load('location');

		return new JobResource($job);
	}

	public function update(UpdateJobRequest $request, Job $job)
	{
		$this->authorize('update', $job);

		$job = (new UpdateJobAction)->execute($job, $request->validated());

		return new JobResource($job->load('location'));
	}

	public function toggle(Job $job)
	{
		$this->authorize('update', $job);

		(new ToggleJobAction)->execute($job);

		return response()->json(null, 204);
	}

	public function destroy(Job $job)
	{
		$this->authorize('delete', $job);

		(new DeleteJobAction)->execute($job);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$job = Job::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$this->authorize('restore', $job);

		$job->restore();

		return new JobResource($job->load('location'));
	}

	public function reorder(ReorderJobRequest $request)
	{
		$this->authorize('create', Job::class);

		(new ReorderJobAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
