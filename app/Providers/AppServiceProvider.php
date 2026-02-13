<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		//
	}

	public function boot(): void
	{
		Gate::policy(\App\Models\Project::class, \App\Policies\ProjectPolicy::class);
		Gate::policy(\App\Models\Location::class, \App\Policies\LocationPolicy::class);
		Gate::policy(\App\Models\Category::class, \App\Policies\CategoryPolicy::class);
		Gate::policy(\App\Models\Status::class, \App\Policies\StatusPolicy::class);
		Gate::policy(\App\Models\TeamMember::class, \App\Policies\TeamMemberPolicy::class);
		Gate::policy(\App\Models\Job::class, \App\Policies\JobPolicy::class);
		Gate::policy(\App\Models\Talk::class, \App\Policies\TalkPolicy::class);
		Gate::policy(\App\Models\Award::class, \App\Policies\AwardPolicy::class);
		Gate::policy(\App\Models\Jury::class, \App\Policies\JuryPolicy::class);
		Gate::policy(\App\Models\NetworkEntry::class, \App\Policies\NetworkEntryPolicy::class);
		Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
		Gate::policy(\App\Models\Post::class, \App\Policies\PostPolicy::class);
		Gate::policy(\App\Models\Section::class, \App\Policies\SectionPolicy::class);
		Gate::policy(\App\Models\Media::class, \App\Policies\MediaPolicy::class);
	}
}
