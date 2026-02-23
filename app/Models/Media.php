<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Media extends Model
{
	use HasFactory, HasUuid, LogsActivity;

	protected $fillable = [
		'uuid',
		'file',
		'original_name',
		'mime_type',
		'size',
		'alt',
		'caption',
		'credits',
		'width',
		'height',
		'is_teaser',
		'is_og',
		'publish',
		'sort_order',
	];

	protected $casts = [
		'is_teaser' => 'boolean',
		'is_og' => 'boolean',
		'publish' => 'boolean',
		'size' => 'integer',
		'width' => 'integer',
		'height' => 'integer',
	];

	public function mediable(): MorphTo
	{
		return $this->morphTo();
	}

	public function getOrientationAttribute(): string
	{
		if (!$this->width || !$this->height) {
			return 'unknown';
		}
		if ($this->width > $this->height) {
			return 'landscape';
		}
		if ($this->height > $this->width) {
			return 'portrait';
		}
		return 'square';
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
