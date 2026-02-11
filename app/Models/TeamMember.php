<?php

namespace App\Models;

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

class TeamMember extends Model
{
	use HasFactory, HasUuid, LogsActivity, Sortable, SoftDeletes;

	protected $fillable = [
		'firstname',
		'name',
		'email',
		'title',
		'since',
		'location_id',
		'slug',
		'publish',
		'sort_order',
	];

	protected $casts = [
		'publish' => 'boolean',
		'since' => 'integer',
	];

	public function bios(): HasMany
	{
		return $this->hasMany(TeamMemberBio::class)->orderBy('sort_order');
	}

	public function media(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
	}

	public function location(): BelongsTo
	{
		return $this->belongsTo(Location::class);
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
