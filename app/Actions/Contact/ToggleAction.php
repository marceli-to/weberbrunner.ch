<?php

namespace App\Actions\Contact;

use App\Models\Contact;

class ToggleAction
{
	public function execute(Contact $contact): void
	{
		$contact->update(['publish' => !$contact->publish]);
	}
}
