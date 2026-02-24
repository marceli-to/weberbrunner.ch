<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Location extends Model
{
	use HasFactory, HasUuid, LogsActivity, Sortable, SoftDeletes;

	protected $fillable = [
		'title',
		'slug',
		'sort_order',
	];

	public function projects(): HasMany
	{
		return $this->hasMany(Project::class);
	}

	public function teamMembers(): HasMany
	{
		return $this->hasMany(TeamMember::class);
	}

	public function jobs(): HasMany
	{
		return $this->hasMany(Job::class);
	}

	public function contacts(): HasMany
	{
		return $this->hasMany(Contact::class);
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
