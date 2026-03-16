<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Masterdata extends Model
{
	use HasFactory, HasUuid, LogsActivity, Sortable, SoftDeletes;

	protected $table = 'masterdata';

	protected $fillable = [
		'title',
		'masterdata_group_id',
		'is_default',
		'sort_order',
	];

	protected $casts = [
		'is_default' => 'boolean',
	];

	public function masterdataGroup(): BelongsTo
	{
		return $this->belongsTo(MasterdataGroup::class);
	}

	public function projects(): BelongsToMany
	{
		return $this->belongsToMany(Project::class, 'masterdata_project')
			->withPivot('value')
			->withTimestamps();
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
