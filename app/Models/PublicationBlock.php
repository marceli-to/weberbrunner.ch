<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PublicationBlock extends Model
{
	use HasFactory, HasUuid, Sortable;

	protected $fillable = [
		'publication_id',
		'type',
		'title',
		'content',
		'url',
		'sort_order',
	];

	public function publication(): BelongsTo
	{
		return $this->belongsTo(Publication::class);
	}

	public function links(): HasMany
	{
		return $this->hasMany(PublicationBlockLink::class)->orderBy('sort_order');
	}

	public function media(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
	}
}
