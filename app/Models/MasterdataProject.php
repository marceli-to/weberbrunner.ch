<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MasterdataProject extends Pivot
{
	protected $table = 'masterdata_project';

	protected $fillable = [
		'project_id',
		'masterdata_id',
		'value',
		'sort_order',
		'publish',
	];

	protected $casts = [
		'publish' => 'boolean',
	];

	public function masterdata(): BelongsTo
	{
		return $this->belongsTo(Masterdata::class);
	}

	public function project(): BelongsTo
	{
		return $this->belongsTo(Project::class);
	}
}
