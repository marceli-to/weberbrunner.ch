<?php

namespace App\Traits;

trait Sortable
{
	public static function bootSortable(): void
	{
		static::creating(function ($model) {
			if (is_null($model->sort_order)) {
				$model->sort_order = static::max('sort_order') + 1;
			}
		});
	}
}
