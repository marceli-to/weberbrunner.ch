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

class MasterdataGroup extends Model
{
	use HasFactory, HasUuid, LogsActivity, Sortable, SoftDeletes;

	protected $fillable = [
		'title',
		'sort_order',
	];

	public function masterdata(): HasMany
	{
		return $this->hasMany(Masterdata::class);
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
