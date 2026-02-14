<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AwardController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\JuryController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\NetworkEntryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProjectAttributeController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\TalkController;
use App\Http\Controllers\Api\TeamMemberBioController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ActivityController;

Route::prefix('dashboard')
	->middleware(['web', 'auth'])
	->group(function () {

		// Blog
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

		// Media
		Route::controller(MediaController::class)
			->prefix('media')
			->group(function () {
				Route::post('/upload', 'upload');
				Route::put('/{media}', 'update');
				Route::delete('/{media}', 'destroy');
				Route::patch('/reorder', 'reorder');
				Route::patch('/{media}/teaser', 'teaser');
			});

		// Locations
		Route::controller(LocationController::class)
			->prefix('locations')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{location}', 'show');
				Route::put('/{location}', 'update');
				Route::delete('/{location}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Projects
		Route::controller(ProjectController::class)
			->prefix('projects')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{project}', 'show');
				Route::put('/{project}', 'update');
				Route::delete('/{project}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Project Attributes (nested under projects)
		Route::controller(ProjectAttributeController::class)
			->prefix('projects/{project}/attributes')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::put('/{attribute}', 'update');
				Route::delete('/{attribute}', 'destroy');
			});

		// Categories
		Route::controller(CategoryController::class)
			->prefix('categories')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{category}', 'show');
				Route::put('/{category}', 'update');
				Route::delete('/{category}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Statuses
		Route::controller(StatusController::class)
			->prefix('statuses')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{status}', 'show');
				Route::put('/{status}', 'update');
				Route::delete('/{status}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Team Members
		Route::controller(TeamMemberController::class)
			->prefix('team')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{teamMember}', 'show');
				Route::put('/{teamMember}', 'update');
				Route::delete('/{teamMember}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Team Member Bios (nested under team members)
		Route::controller(TeamMemberBioController::class)
			->prefix('team/{teamMember}/cv')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::put('/{bio}', 'update');
				Route::delete('/{bio}', 'destroy');
			});

		// Jobs
		Route::controller(JobController::class)
			->prefix('jobs')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{job}', 'show');
				Route::put('/{job}', 'update');
				Route::delete('/{job}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Talks
		Route::controller(TalkController::class)
			->prefix('talks')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{talk}', 'show');
				Route::put('/{talk}', 'update');
				Route::delete('/{talk}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Awards
		Route::controller(AwardController::class)
			->prefix('awards')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{award}', 'show');
				Route::put('/{award}', 'update');
				Route::patch('/{award}/toggle', 'toggle');
				Route::delete('/{award}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Jury
		Route::controller(JuryController::class)
			->prefix('jury')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{jury}', 'show');
				Route::put('/{jury}', 'update');
				Route::delete('/{jury}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Sections
		Route::controller(SectionController::class)
			->prefix('sections')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{section}', 'show');
				Route::put('/{section}', 'update');
				Route::delete('/{section}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Network
		Route::controller(NetworkEntryController::class)
			->prefix('network')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{networkEntry}', 'show');
				Route::put('/{networkEntry}', 'update');
				Route::delete('/{networkEntry}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Users (admin only)
		Route::controller(UserController::class)
			->prefix('users')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::get('/{user}', 'show');
				Route::put('/{user}', 'update');
				Route::delete('/{user}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Activity Log (admin only)
		Route::get('/activity', [ActivityController::class, 'index']);

	});
