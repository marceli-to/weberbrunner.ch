<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Award extends Model
{
	use HasFactory, HasUuid, LogsActivity, Sortable, SoftDeletes;

	protected $fillable = [
		'title',
		'description',
		'year',
		'project_id',
		'link',
		'publish',
		'sort_order',
	];

	protected $casts = [
		'publish' => 'boolean',
		'year' => 'integer',
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
