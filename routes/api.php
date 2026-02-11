<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\MediaController;

Route::prefix('dashboard')
	->middleware(['web', 'auth'])
	->group(function () {

		Route::controller(PostController::class)
			->prefix('blog')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{post}', 'show');
				Route::put('/{post}', 'update');
				Route::delete('/{post}', 'destroy');
			});

		Route::controller(MediaController::class)
			->prefix('media')
			->group(function () {
				Route::post('/upload', 'upload');
				Route::put('/{media}', 'update');
				Route::delete('/{media}', 'destroy');
				Route::patch('/reorder', 'reorder');
				Route::patch('/{media}/teaser', 'teaser');
			});

	});
