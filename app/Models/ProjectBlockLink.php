<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectBlockLink extends Model
{
	use HasFactory, HasUuid, Sortable;

	protected $fillable = [
		'project_block_id',
		'title',
		'url',
		'link_type',
		'linked_project_id',
		'sort_order',
	];

	public function block(): BelongsTo
	{
		return $this->belongsTo(ProjectBlock::class, 'project_block_id');
	}

	public function linkedProject(): BelongsTo
	{
		return $this->belongsTo(Project::class, 'linked_project_id');
	}
}
