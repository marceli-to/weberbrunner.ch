<?php

namespace App\Http\Requests\Block;

use Illuminate\Database\Eloquent\Model;

trait ResolvesBlockable
{
	protected function blockable(): Model
	{
		foreach (['project', 'publication', 'pageText'] as $param) {
			$parent = $this->route($param);

			if ($parent instanceof Model) {
				return $parent;
			}
		}

		abort(404);
	}
}
