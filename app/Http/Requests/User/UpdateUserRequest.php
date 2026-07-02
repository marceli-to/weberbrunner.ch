<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends StoreUserRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('user'));
	}

	public function rules(): array
	{
		$user = $this->route('user');

		$rules = [
			'type' => 'required|in:intern,extern',
			'role' => 'required|in:admin,editor,viewer',
			'password' => 'nullable|string|min:8',
		];

		if ($this->input('type') === 'intern') {
			$rules['team_member_id'] = [
				'required',
				'exists:team_members,id',
				Rule::unique('users', 'team_member_id')->ignore($user->id)->whereNull('deleted_at'),
			];
		} else {
			$rules['firstname'] = 'nullable|string|max:255';
			$rules['name'] = 'required|string|max:255';
			$rules['email'] = ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)];
		}

		return $rules;
	}

	protected function emailTaken(string $email): bool
	{
		return User::where('email', $email)
			->where('id', '!=', $this->route('user')->id)
			->exists();
	}
}
