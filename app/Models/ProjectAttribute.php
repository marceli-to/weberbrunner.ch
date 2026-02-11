<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectAttribute extends Model
{
	use HasFactory, HasUuid, Sortable;

	protected $fillable = [
		'project_id',
		'label',
		'value',
		'sort_order',
	];

	public function project(): BelongsTo
	{
		return $this->belongsTo(Project::class);
	}
}
