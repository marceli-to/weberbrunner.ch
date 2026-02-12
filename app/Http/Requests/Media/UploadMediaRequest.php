<?php

namespace App\Http\Requests\Media;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Media::class);
	}

	public function rules(): array
	{
		return [
			'file' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:51200',
		];
	}
}
