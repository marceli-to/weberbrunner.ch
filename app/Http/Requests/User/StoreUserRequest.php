<?php

namespace App\Http\Requests\User;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', User::class);
	}

	protected function prepareForValidation(): void
	{
		if (!$this->has('type')) {
			$this->merge(['type' => 'extern']);
		}
	}

	public function rules(): array
	{
		$rules = [
			'type' => 'required|in:intern,extern',
			'role' => 'required|in:admin,editor,viewer',
			'password' => 'required|string|min:8',
		];

		if ($this->input('type') === 'intern') {
			$rules['team_member_id'] = [
				'required',
				'exists:team_members,id',
				Rule::unique('users', 'team_member_id')->whereNull('deleted_at'),
			];
		} else {
			$rules['firstname'] = 'nullable|string|max:255';
			$rules['name'] = 'required|string|max:255';
			$rules['email'] = ['required', 'email', 'max:255', Rule::unique('users', 'email')];
		}

		return $rules;
	}

	public function withValidator(Validator $validator): void
	{
		$validator->after(function (Validator $validator) {
			if ($this->input('type') !== 'intern') {
				return;
			}

			$member = TeamMember::find($this->integer('team_member_id'));

			if (!$member) {
				return;
			}

			if (!$member->email) {
				$validator->errors()->add('team_member_id', 'Dieses Teammitglied hat keine E-Mail-Adresse.');

				return;
			}

			if ($this->emailTaken($member->email)) {
				$validator->errors()->add('team_member_id', 'Für diese E-Mail-Adresse existiert bereits ein Konto.');
			}
		});
	}

	protected function emailTaken(string $email): bool
	{
		return User::where('email', $email)->exists();
	}
}
