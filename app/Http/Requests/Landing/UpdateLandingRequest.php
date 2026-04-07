<?php

namespace App\Http\Requests\Landing;

use App\Models\Landing;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLandingRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', Landing::class);
	}

	public function rules(): array
	{
		return [
			'text' => 'nullable|string',
			'publish' => 'sometimes|boolean',
		];
	}
}
