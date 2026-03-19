<?php

namespace App\Http\Requests\PublicationBlockLink;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublicationBlockLinkRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('publication'));
	}

	public function rules(): array
	{
		return [
			'title' => 'nullable|string|max:255',
			'url' => 'nullable|string|max:2048',
			'link_type' => 'required|string|in:internal,external',
			'linked_project_id' => 'nullable|integer|exists:projects,id',
		];
	}

	public function messages(): array
	{
		return [
			'title.max' => 'Bitte überprüfe den Titel',
			'url.max' => 'Bitte überprüfe die URL',
			'link_type.required' => 'Bitte einen Link-Typ angeben',
			'link_type.in' => 'Ungültiger Link-Typ',
		];
	}
}
