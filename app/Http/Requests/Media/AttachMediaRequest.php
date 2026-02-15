<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

class AttachMediaRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route()->parameter('teamMember'));
	}

	public function rules(): array
	{
		return [
			'media' => 'required|array',
			'media.*.uuid' => 'required|string',
			'media.*.file' => 'required|string',
			'media.*.original_name' => 'required|string',
			'media.*.mime_type' => 'required|string',
			'media.*.size' => 'required|integer',
			'media.*.width' => 'nullable|integer',
			'media.*.height' => 'nullable|integer',
		];
	}
}
