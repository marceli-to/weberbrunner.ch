<?php

namespace App\Http\Controllers\Api;

use App\Actions\Job\DeleteAction as DeleteJobAction;
use App\Actions\Job\ReorderAction as ReorderJobAction;
use App\Actions\Job\StoreAction as StoreJobAction;
use App\Actions\Job\UpdateAction as UpdateJobAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Job\StoreJobRequest;
use App\Http\Requests\Job\UpdateJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;

class JobController extends Controller
{
	public function index()
	{
		$jobs = Job::with('location')->orderBy('sort_order')->get();

		return JobResource::collection($jobs);
	}

	public function store(StoreJobRequest $request)
	{
		$job = (new StoreJobAction)->execute($request->validated());

		return new JobResource($job->load('location'));
	}

	public function show(Job $job)
	{
		$job->load('location');

		return new JobResource($job);
	}

	public function update(UpdateJobRequest $request, Job $job)
	{
		$job = (new UpdateJobAction)->execute($job, $request->validated());

		return new JobResource($job->load('location'));
	}

	public function destroy(Job $job)
	{
		(new DeleteJobAction)->execute($job);

		return response()->json(null, 204);
	}

	public function restore(string $uuid)
	{
		$job = Job::withTrashed()->where('uuid', $uuid)->firstOrFail();
		$job->restore();

		return new JobResource($job->load('location'));
	}

	public function reorder()
	{
		(new ReorderJobAction)->execute(request('items'));

		return response()->json(null, 204);
	}
}
