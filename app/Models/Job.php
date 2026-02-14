<?php

namespace App\Models;

use App\Traits\HasPublishScope;
use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Job extends Model
{
	use HasFactory, HasPublishScope, HasUuid, LogsActivity, Sortable, SoftDeletes;

	protected $table = 'job_listings';

	protected $fillable = [
		'title',
		'description',
		'location_id',
		'contact_email',
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

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
