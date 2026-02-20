<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProjectBlock extends Model
{
	use HasFactory, HasUuid, Sortable;

	protected $fillable = [
		'project_id',
		'type',
		'title',
		'content',
		'sort_order',
	];

	public function project(): BelongsTo
	{
		return $this->belongsTo(Project::class);
	}

	public function links(): HasMany
	{
		return $this->hasMany(ProjectBlockLink::class)->orderBy('sort_order');
	}

	public function media(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
	}
}
