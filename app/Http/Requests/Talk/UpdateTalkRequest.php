<?php

namespace App\Http\Requests\Talk;

use App\Models\Talk;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTalkRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('talk'));
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'event' => 'nullable|string|max:255',
			'location' => 'nullable|string|max:255',
			'date' => 'required|date',
			'link' => 'nullable|url|max:255',
			'publish' => 'boolean',
		];
	}
}
