<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Landing extends Model
{
	use HasUuid, LogsActivity;

	protected $fillable = [
		'text',
		'publish',
	];

	protected function casts(): array
	{
		return [
			'publish' => 'boolean',
		];
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
