<?php

namespace App\Models;

use App\Traits\HasPublishScope;
use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Publication extends Model
{
	use HasFactory, HasPublishScope, HasUuid, LogsActivity, Sortable, SoftDeletes;

	protected $fillable = [
		'title',
		'slug',
		'subtitle',
		'meta_description',
		'location_id',
		'publish',
		'sort_order',
	];

	protected $casts = [
		'publish' => 'boolean',
	];

	public function location(): BelongsTo
	{
		return $this->belongsTo(Location::class);
	}

	public function attributes(): HasMany
	{
		return $this->hasMany(PublicationAttribute::class)->orderBy('sort_order');
	}

	public function blocks(): HasMany
	{
		return $this->hasMany(PublicationBlock::class)->orderBy('sort_order');
	}

	public function media(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
	}

	public function teaser(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->where('is_teaser', true);
	}

	public function og(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->where('is_og', true);
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
