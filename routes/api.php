<?php

use App\Models\PageText;
use App\Models\Project;
use App\Models\Publication;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AwardController;
use App\Http\Controllers\Api\BlockController;
use App\Http\Controllers\Api\BlockLinkController;
use App\Http\Controllers\Api\BlockMediaController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\JuryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\ProjectCategoryController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProjectMediaController;
use App\Http\Controllers\Api\ProjectMetaController;
use App\Http\Controllers\Api\ProjectTextController;
use App\Http\Controllers\Api\ProjectStatusController;
use App\Http\Controllers\Api\PublicationAttributeController;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\PublicationMediaController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\TalkController;
use App\Http\Controllers\Api\TeamMemberBioController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\LandingItemController;
use App\Http\Controllers\Api\PageTextController;
use App\Http\Controllers\Api\MasterdataController;
use App\Http\Controllers\Api\MasterdataGroupController;
use App\Http\Controllers\Api\ProjectMasterdataController;

Route::bind('project', fn ($value) => Project::where('uuid', $value)->firstOrFail());
Route::bind('publication', fn ($value) => Publication::where('uuid', $value)->firstOrFail());
Route::bind('pageText', fn ($value) => PageText::where('page', $value)->firstOrFail());

Route::prefix('dashboard')
	->middleware(['web', 'auth', \App\Http\Middleware\RestrictPublishToAdmin::class])
	->group(function () {

		$blockRoutes = function () {
			Route::controller(BlockController::class)
				->prefix('blocks')
				->group(function () {
					Route::get('/', 'index');
					Route::post('/', 'store');
					Route::patch('/reorder', 'reorder');
					Route::put('/{block}', 'update');
					Route::delete('/{block}', 'destroy');
				});

			Route::controller(BlockMediaController::class)
				->prefix('blocks/{block}')
				->group(function () {
					Route::post('/media/select', 'select');
					Route::post('/media/upload', 'upload');
					Route::delete('/media/{media}', 'detach');
				});

			Route::controller(BlockLinkController::class)
				->prefix('blocks/{block}/links')
				->group(function () {
					Route::post('/', 'store');
					Route::patch('/reorder', 'reorder');
					Route::put('/{link}', 'update');
					Route::patch('/{link}/toggle', 'toggle');
					Route::delete('/{link}', 'destroy');
				});
		};

		// Landing
		Route::controller(LandingItemController::class)
			->prefix('landing')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::delete('/{landingItem}', 'destroy');
			});

		// Page Text
		Route::controller(PageTextController::class)
			->prefix('page-text')
			->group(function () {
				Route::get('/{page}', 'show');
				Route::put('/{page}', 'update');
			});

		// Media
		Route::controller(MediaController::class)
			->prefix('media')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/upload', 'upload');
				Route::post('/persist', 'persist');
				Route::put('/{media}', 'update');
				Route::delete('/{media}', 'destroy');
				Route::patch('/reorder', 'reorder');
				Route::patch('/{media}/teaser', 'teaser');
				Route::patch('/{media}/og', 'og');
				Route::patch('/{media}/publish', 'togglePublish');
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
				Route::get('/published', 'published');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{project}', 'show');
				Route::put('/{project}', 'update');
				Route::delete('/{project}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
				Route::patch('/{project}/toggle', 'toggle');
			});

		// Project Media
		Route::controller(ProjectMediaController::class)
			->prefix('projects/{project}')
			->group(function () {
				Route::post('/media', 'attach');
			});

		// Project Meta
		Route::controller(ProjectMetaController::class)
			->prefix('projects/{project}')
			->group(function () {
				Route::patch('/meta-description', 'update');
			});

		// Project Text
		Route::controller(ProjectTextController::class)
			->prefix('projects/{project}')
			->group(function () {
				Route::patch('/text', 'update');
			});

		// Project Categories
		Route::controller(ProjectCategoryController::class)
			->prefix('projects/{project}')
			->group(function () {
				Route::patch('/categories', 'sync');
			});

		// Project Statuses
		Route::controller(ProjectStatusController::class)
			->prefix('projects/{project}')
			->group(function () {
				Route::patch('/statuses', 'sync');
			});

		// Project Masterdata
		Route::controller(ProjectMasterdataController::class)
			->prefix('projects/{project}')
			->group(function () {
				Route::get('/masterdata', 'all');
				Route::get('/masterdata/attached', 'attached');
				Route::get('/masterdata/available', 'available');
				Route::patch('/masterdata', 'updateValues');
				Route::patch('/masterdata/reorder', 'reorder');
				Route::post('/masterdata/{masterdata}', 'attach');
				Route::delete('/masterdata/{masterdata}', 'destroy');
			});

		// Project Blocks (shared BlockController)
		Route::prefix('projects/{project}')->group($blockRoutes);

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

		// Publications
		Route::controller(PublicationController::class)
			->prefix('publications')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{publication}', 'show');
				Route::put('/{publication}', 'update');
				Route::patch('/{publication}/toggle', 'toggle');
				Route::delete('/{publication}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Publication Media
		Route::controller(PublicationMediaController::class)
			->prefix('publications/{publication}')
			->group(function () {
				Route::post('/media', 'attach');
			});

		// Publication Attributes
		Route::controller(PublicationAttributeController::class)
			->prefix('publications/{publication}/attributes')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::put('/{attribute}', 'update');
				Route::delete('/{attribute}', 'destroy');
			});

		// Publication Blocks (shared BlockController)
		Route::prefix('publications/{publication}')->group($blockRoutes);

		// Page Blocks (shared BlockController — Office and other pages)
		Route::prefix('pages/{pageText}')->group($blockRoutes);

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
				Route::post('/{teamMember}/media', 'attachMedia');
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
				Route::patch('/{job}/toggle', 'toggle');
				Route::delete('/{job}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Contacts
		Route::controller(ContactController::class)
			->prefix('contacts')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{contact}', 'show');
				Route::put('/{contact}', 'update');
				Route::patch('/{contact}/toggle', 'toggle');
				Route::delete('/{contact}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
				Route::post('/{contact}/media', 'attachMedia');
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
				Route::patch('/{talk}/toggle', 'toggle');
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
				Route::patch('/{jury}/toggle', 'toggle');
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

		// Masterdata
		Route::controller(MasterdataController::class)
			->prefix('masterdata')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{masterdata}', 'show');
				Route::put('/{masterdata}', 'update');
				Route::patch('/{masterdata}/standard', 'toggleStandard');
				Route::delete('/{masterdata}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Masterdata Groups
		Route::controller(MasterdataGroupController::class)
			->prefix('masterdata-groups')
			->group(function () {
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::patch('/reorder', 'reorder');
				Route::get('/{masterdataGroup}', 'show');
				Route::put('/{masterdataGroup}', 'update');
				Route::delete('/{masterdataGroup}', 'destroy');
				Route::patch('/{uuid}/restore', 'restore');
			});

		// Current user
		Route::get('/me', function () {
			return new \App\Http\Resources\UserResource(auth()->user());
		});

		// Activity Log (admin only)
		Route::get('/activity', [ActivityController::class, 'index']);

	});
