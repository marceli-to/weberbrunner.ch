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

class Section extends Model
{
	use HasFactory, HasUuid, LogsActivity, Sortable, SoftDeletes;

	protected $fillable = [
		'title',
		'type',
		'sort_order',
	];

	public function awards(): HasMany
	{
		return $this->hasMany(Award::class);
	}

	public function juries(): HasMany
	{
		return $this->hasMany(Jury::class);
	}

	public function talks(): HasMany
	{
		return $this->hasMany(Talk::class);
	}

	public function networkEntries(): HasMany
	{
		return $this->hasMany(NetworkEntry::class);
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
