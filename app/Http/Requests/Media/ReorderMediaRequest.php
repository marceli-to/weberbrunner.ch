<?php

namespace App\Http\Requests\Media;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;

class ReorderMediaRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Media::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:media,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}
