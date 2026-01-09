<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/img/{path}', [ImageController::class, 'show'])->where('path', '.*');

Route::view('/', 'pages.landing')->name('page.landing');

Route::view('/arbeiten', 'pages.works.index')->name('page.works');
Route::view('/arbeiten/{slug}', 'pages.works.show')->name('page.works.show');

Route::view('/buero', 'pages.about.index')->name('page.about');
Route::view('/buero/team', 'pages.about.team')->name('page.about.team');
Route::view('/buero/jobs', 'pages.about.jobs')->name('page.about.jobs');
Route::view('/buero/kontakt', 'pages.about.contact')->name('page.about.contact');
Route::view('/buero/netzwerk', 'pages.about.network')->name('page.about.network');
Route::view('/buero/vortraege', 'pages.about.talks')->name('page.about.talks');
Route::view('/buero/jury', 'pages.about.jury')->name('page.about.jury');
Route::view('/buero/auszeichnungen', 'pages.about.awards')->name('page.about.awards');

Route::view('/suche', 'pages.search')->name('page.search');
