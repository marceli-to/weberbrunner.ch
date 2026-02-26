<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LandingItem extends Model
{
	use HasFactory, HasUuid, LogsActivity, Sortable;

	protected $fillable = [
		'project_id',
		'column',
		'sort_order',
	];

	public function project(): BelongsTo
	{
		return $this->belongsTo(Project::class);
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
