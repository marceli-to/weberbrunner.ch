<?php

namespace App\Http\Requests\Media;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('media'));
	}

	public function rules(): array
	{
		return [
			'alt' => 'nullable|string|max:255',
			'caption' => 'nullable|string|max:255',
		];
	}
}
