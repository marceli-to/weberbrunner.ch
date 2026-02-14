<?php

namespace App\Http\Requests\Job;

use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;

class ReorderJobRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Job::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:job_listings,id',
			'items.*.sort_order' => 'required|integer',
			'items.*.location_id' => 'sometimes|integer|exists:locations,id',
		];
	}
}
