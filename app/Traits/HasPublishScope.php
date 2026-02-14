<?php

namespace App\Traits;

trait HasPublishScope
{
	public function scopePublished($query)
	{
		return $query->where('publish', true);
	}
}
