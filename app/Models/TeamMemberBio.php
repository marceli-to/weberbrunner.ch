<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TeamMemberBio extends Model
{
	use HasFactory, HasUuid, LogsActivity, Sortable;

	protected $fillable = [
		'team_member_id',
		'period',
		'description',
		'sort_order',
	];

	public function teamMember(): BelongsTo
	{
		return $this->belongsTo(TeamMember::class);
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty();
	}
}
