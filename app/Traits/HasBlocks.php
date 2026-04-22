<?php

namespace App\Traits;

use App\Models\Block;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasBlocks
{
	public function blocks(): MorphMany
	{
		return $this->morphMany(Block::class, 'blockable')->orderBy('sort_order');
	}

	public function allowedBlockTypes(): array
	{
		return ['text', 'slider', 'image', 'links', 'fixed-slider'];
	}
}
