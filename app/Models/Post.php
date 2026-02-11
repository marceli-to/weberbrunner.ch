<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Post extends Model
{
	use HasFactory, LogsActivity;

	protected $fillable = ['title', 'slug', 'content', 'publish', 'sort_order'];

	protected $casts = [
		'publish' => 'boolean',
	];

	public function media(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
	}

	public function teaser(): MorphMany
	{
		return $this->morphMany(Media::class, 'mediable')->where('is_teaser', true);
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
