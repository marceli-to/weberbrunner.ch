<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicationAttribute extends Model
{
	use HasFactory, HasUuid, Sortable;

	protected $fillable = [
		'publication_id',
		'key',
		'value',
		'sort_order',
	];

	public function publication(): BelongsTo
	{
		return $this->belongsTo(Publication::class);
	}
}
