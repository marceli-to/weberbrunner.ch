<?php

namespace App\Models;

use App\Traits\HasPublishScope;
use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Contact extends Model
{
	use HasFactory, HasPublishScope, HasUuid, LogsActivity, Sortable, SoftDeletes;

	protected $fillable = [
		'location_id',
		'company_name',
		'address',
		'phone',
		'email',
		'maps_url',
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

	public function media(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
	}

	public function image(): MorphOne
	{
		return $this->morphOne(Media::class, 'mediable');
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
