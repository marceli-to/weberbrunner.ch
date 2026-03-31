<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\ProjectPreviewController;
use App\Http\Controllers\PublicationPreviewController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JuryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TalkController;
use App\Http\Controllers\TeamController;

Route::get('/img/{path}', [ImageController::class, 'show'])->where('path', '.*');

Route::get('/', LandingController::class)->name('page.landing');

Route::prefix('vorschau')->name('page.preview')->group(function () {
	Route::get('/publikationen/{slug}', [PublicationPreviewController::class, 'show'])->name('.publications');
	Route::get('/{slug}', [ProjectPreviewController::class, 'show'])->name('.show');
});

// Works
Route::prefix('arbeiten')->name('page.works')->group(function () {
	Route::view('/', 'pages.works.index');
	Route::get('/{slug}', [ProjectController::class, 'show'])->name('.show');
});

// About
Route::prefix('buero')->name('page.about')->group(function () {
	Route::view('/', 'pages.about.index');
	Route::view('/team', 'pages.about.team.index')->name('.team');
	Route::get('/team/{slug}', [TeamController::class, 'show'])->name('.team.show');
	Route::get('/jobs', JobController::class)->name('.jobs');
	Route::get('/kontakt', ContactController::class)->name('.contact');
	Route::view('/netzwerk', 'pages.about.network')->name('.network');
	Route::get('/vortraege', TalkController::class)->name('.talks');
	Route::get('/jury', JuryController::class)->name('.jury');
	Route::get('/auszeichnungen', AwardController::class)->name('.awards');
	Route::get('/publikationen', [PublicationController::class, 'index'])->name('.publications');
	Route::get('/publikationen/{slug}', [PublicationController::class, 'show'])->name('.publications.show');
});

// Legal
Route::name('page.privacy.')->group(function () {
	Route::view('/impressum', 'pages.misc.imprint')->name('imprint');
	Route::view('/datenschutz', 'pages.misc.privacy')->name('privacy');
});

// Dashboard (Vue SPA) — requires authentication
Route::middleware('auth')->group(function () {
	Route::get('/dashboard/{any?}', function () {
		return view('components.layout.app');
	})->where('any', '.*')->name('dashboard');
});

require __DIR__.'/auth.php';
