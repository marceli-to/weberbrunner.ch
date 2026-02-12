<?php

namespace App\Http\Requests\Status;

use App\Models\Status;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('status'));
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
		];
	}
}
