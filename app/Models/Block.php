<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Block extends Model
{
	use HasFactory, HasUuid, Sortable;

	protected $fillable = [
		'type',
		'title',
		'content',
		'url',
		'sort_order',
	];

	public function blockable(): MorphTo
	{
		return $this->morphTo();
	}

	public function links(): HasMany
	{
		return $this->hasMany(BlockLink::class)->orderBy('sort_order');
	}

	public function media(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
	}
}
