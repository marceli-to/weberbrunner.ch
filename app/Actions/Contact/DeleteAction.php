<?php

namespace App\Actions\Contact;

use App\Models\Contact;

class DeleteAction
{
	public function execute(Contact $contact): void
	{
		$contact->delete();
	}
}
