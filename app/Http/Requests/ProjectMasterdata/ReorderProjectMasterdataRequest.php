<?php

namespace App\Http\Requests\ProjectMasterdata;

use Illuminate\Foundation\Http\FormRequest;

class ReorderProjectMasterdataRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'items' => ['required', 'array'],
			'items.*.uuid' => ['required', 'string', 'exists:masterdata,uuid'],
			'items.*.sort_order' => ['required', 'integer'],
		];
	}
}
