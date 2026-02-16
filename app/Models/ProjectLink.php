<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectLink extends Model
{
	use HasFactory, HasUuid, Sortable;

	protected $fillable = [
		'project_id',
		'url',
		'sort_order',
	];

	public function project(): BelongsTo
	{
		return $this->belongsTo(Project::class);
	}
}
