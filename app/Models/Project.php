<?php

namespace App\Models;

use App\Traits\HasPublishScope;
use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
	use HasFactory, HasPublishScope, HasUuid, LogsActivity, Sortable, SoftDeletes;

	protected $fillable = [
		'priority',
		'title',
		'number',
		'slug',
		'short_description',
		'description',
		'meta_description',
		'city',
		'location_id',
		'publish',
		'sort_order',
	];

	protected $casts = [
		'number' => 'integer',
		'publish' => 'boolean',
	];

	protected function fullTitle(): Attribute
	{
		return Attribute::make(
			get: fn () => $this->city ? "{$this->title}, {$this->city}" : $this->title,
		);
	}

	public function location(): BelongsTo
	{
		return $this->belongsTo(Location::class);
	}

	public function attributes(): HasMany
	{
		return $this->hasMany(ProjectAttribute::class)->orderBy('sort_order');
	}

	public function links(): HasMany
	{
		return $this->hasMany(ProjectLink::class)->orderBy('sort_order');
	}

	public function blocks(): HasMany
	{
		return $this->hasMany(ProjectBlock::class)->orderBy('sort_order');
	}

	public function media(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
	}

	public function teaser(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->where('is_teaser', true);
	}

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class);
	}

	public function statuses(): BelongsToMany
	{
		return $this->belongsToMany(Status::class);
	}

	public function masterdata(): BelongsToMany
	{
		return $this->belongsToMany(Masterdata::class, 'masterdata_project')
			->using(MasterdataProject::class)
			->withPivot('value', 'sort_order', 'publish')
			->withTimestamps();
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
