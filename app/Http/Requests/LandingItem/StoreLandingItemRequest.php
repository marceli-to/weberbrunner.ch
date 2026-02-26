<?php

namespace App\Http\Requests\LandingItem;

use App\Models\LandingItem;
use Illuminate\Foundation\Http\FormRequest;

class StoreLandingItemRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', LandingItem::class);
	}

	public function rules(): array
	{
		return [
			'project_id' => 'required|integer|exists:projects,id|unique:landing_items,project_id',
			'column' => 'required|integer|in:1,2,3',
		];
	}
}
