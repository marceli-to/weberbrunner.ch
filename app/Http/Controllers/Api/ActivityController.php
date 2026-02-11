<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
	public function index()
	{
		$query = Activity::with('causer', 'subject')
			->latest();

		if (request('subject_type')) {
			$query->where('subject_type', request('subject_type'));
		}

		if (request('causer_id')) {
			$query->where('causer_id', request('causer_id'));
		}

		if (request('from')) {
			$query->where('created_at', '>=', request('from'));
		}

		if (request('to')) {
			$query->where('created_at', '<=', request('to'));
		}

		return $query->paginate(50);
	}
}
