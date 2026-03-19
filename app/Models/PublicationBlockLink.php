<?php

namespace App\Models;

use App\Traits\HasPublishScope;
use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicationBlockLink extends Model
{
	use HasFactory, HasPublishScope, HasUuid, Sortable;

	protected $fillable = [
		'publication_block_id',
		'title',
		'url',
		'link_type',
		'linked_project_id',
		'sort_order',
		'publish',
	];

	protected $casts = [
		'publish' => 'boolean',
	];

	public function block(): BelongsTo
	{
		return $this->belongsTo(PublicationBlock::class, 'publication_block_id');
	}

	public function linkedProject(): BelongsTo
	{
		return $this->belongsTo(Project::class, 'linked_project_id');
	}
}
