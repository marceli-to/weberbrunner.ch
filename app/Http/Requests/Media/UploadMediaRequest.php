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
			'file' => 'required|file|mimes:jpg,jpeg,png,webp,gif,pdf|max:51200',
		];
	}

	public function messages(): array
	{
		return [
			'file.required' => 'Bitte wähle eine Datei aus',
			'file.file' => 'Bitte überprüfe die Datei',
			'file.mimes' => 'Bitte überprüfe das Dateiformat (JPG, PNG, WebP, GIF oder PDF)',
			'file.max' => 'Die Datei ist zu gross (max. 50 MB)',
		];
	}
}
