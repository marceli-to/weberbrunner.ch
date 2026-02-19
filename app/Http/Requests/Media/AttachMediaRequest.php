<?php

namespace App\Http\Requests\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class AttachMediaRequest extends FormRequest
{
	public function authorize(): bool
	{
		$parent = collect($this->route()->parameters())->first(fn ($p) => $p instanceof Model);

		return $parent && $this->user()->can('update', $parent);
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
