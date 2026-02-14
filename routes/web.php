<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AwardPageController;
use App\Http\Controllers\JobPageController;
use App\Http\Controllers\JuryPageController;
use App\Http\Controllers\TalkPageController;
use App\Http\Controllers\TeamController;

Route::get('/img/{path}', [ImageController::class, 'show'])->where('path', '.*');

Route::get('/', LandingController::class)->name('page.landing');

// Works
Route::prefix('arbeiten')->name('page.works')->group(function () {
	Route::view('/', 'pages.works.index');
	Route::get('/{slug}', [ProjectController::class, 'show'])->name('.show');
});

// About
Route::prefix('buero')->name('page.about')->group(function () {
	Route::view('/', 'pages.about.index');
	Route::view('/team', 'pages.about.team')->name('.team');
	Route::get('/team/{slug}', [TeamController::class, 'show'])->name('.team.show');
	Route::get('/jobs', JobPageController::class)->name('.jobs');
	Route::view('/kontakt', 'pages.about.contact')->name('.contact');
	Route::view('/netzwerk', 'pages.about.network')->name('.network');
	Route::get('/vortraege', TalkPageController::class)->name('.talks');
	Route::get('/jury', JuryPageController::class)->name('.jury');
	Route::get('/auszeichnungen', AwardPageController::class)->name('.awards');
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
