<?php

namespace App\Models;

use App\Traits\HasBlocks;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PageText extends Model
{
	use HasBlocks, HasUuid, LogsActivity;

	protected $fillable = [
		'page',
		'title',
		'text',
	];

	public function allowedBlockTypes(): array
	{
		return ['text', 'slider', 'image', 'links'];
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
