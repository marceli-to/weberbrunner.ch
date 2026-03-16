<?php

namespace App\Providers;

use App\Http\Controllers\ImageController;
use App\Models\Award;
use App\Models\Category;
use App\Models\LandingItem;
use App\Models\Masterdata;
use App\Models\MasterdataGroup;
use App\Models\Job;
use App\Models\Jury;
use App\Models\Location;
use App\Models\Media;
use App\Models\NetworkEntry;
use App\Models\Project;
use App\Models\Section;
use App\Models\Status;
use App\Models\Talk;
use App\Models\TeamMember;
use App\Models\User;
use App\Policies\AwardPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\LandingItemPolicy;
use App\Policies\MasterdataPolicy;
use App\Policies\MasterdataGroupPolicy;
use App\Policies\JobPolicy;
use App\Policies\JuryPolicy;
use App\Policies\LocationPolicy;
use App\Policies\MediaPolicy;
use App\Policies\NetworkEntryPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\SectionPolicy;
use App\Policies\StatusPolicy;
use App\Policies\TalkPolicy;
use App\Policies\TeamMemberPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Blade;
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
		Gate::policy(Project::class, ProjectPolicy::class);
		Gate::policy(Location::class, LocationPolicy::class);
		Gate::policy(Category::class, CategoryPolicy::class);
		Gate::policy(Status::class, StatusPolicy::class);
		Gate::policy(TeamMember::class, TeamMemberPolicy::class);
		Gate::policy(Job::class, JobPolicy::class);
		Gate::policy(Talk::class, TalkPolicy::class);
		Gate::policy(Award::class, AwardPolicy::class);
		Gate::policy(Jury::class, JuryPolicy::class);
		Gate::policy(NetworkEntry::class, NetworkEntryPolicy::class);
		Gate::policy(User::class, UserPolicy::class);
		Gate::policy(Section::class, SectionPolicy::class);
		Gate::policy(Media::class, MediaPolicy::class);
		Gate::policy(LandingItem::class, LandingItemPolicy::class);
		Gate::policy(Masterdata::class, MasterdataPolicy::class);
		Gate::policy(MasterdataGroup::class, MasterdataGroupPolicy::class);

		Blade::directive('ogImage', function (string $expression) {
			return "<?php \$__env->startSection('og_image', \App\Http\Controllers\ImageController::ogImageUrl({$expression})); ?>";
		});
	}
}
