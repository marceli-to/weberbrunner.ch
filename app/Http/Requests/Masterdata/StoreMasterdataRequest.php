<?php

namespace App\Http\Requests\Masterdata;

use App\Models\Masterdata;
use Illuminate\Foundation\Http\FormRequest;

class StoreMasterdataRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Masterdata::class);
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'masterdata_group_id' => 'required|exists:masterdata_groups,id',
			'standard' => 'boolean',
		];
	}

	public function messages(): array
	{
		return [
			'title.required' => 'Bitte Titel eingeben',
			'masterdata_group_id.required' => 'Bitte überprüfe die Gruppe',
			'masterdata_group_id.exists' => 'Bitte überprüfe die Gruppe',
		];
	}
}
