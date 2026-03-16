<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
	$this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('media:cleanup')->daily();
Schedule::command('media:purge-orphans --force')->weekly();
Schedule::command('activitylog:clean --days=90')->monthly();
