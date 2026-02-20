<?php

namespace App\Http\Requests\Job;

use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;

class ReorderJobRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Job::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:job_listings,uuid',
			'items.*.sort_order' => 'required|integer',
			'items.*.location_id' => 'sometimes|integer|exists:locations,id',
		];
	}
}
