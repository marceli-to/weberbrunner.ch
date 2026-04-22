<?php

namespace App\Actions\Block;

use App\Models\Block;
use Illuminate\Database\Eloquent\Model;

class StoreAction
{
	public function execute(Model $blockable, array $data): Block
	{
		return $blockable->blocks()->create($data);
	}
}
