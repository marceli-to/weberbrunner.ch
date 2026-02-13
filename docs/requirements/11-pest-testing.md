# Testing: Pest Implementation Guide

## Overview

Concrete Pest test plan for the backend. Covers every API endpoint, model behavior, action class, policy, and trait. Tests use Pest's `describe`/`it` syntax and Laravel's `RefreshDatabase` trait.

---

## Setup

```bash
composer require pestphp/pest --dev
composer require pestphp/pest-plugin-laravel --dev
./vendor/bin/pest --init
```

This creates `tests/Pest.php` (the bootstrap file). Configure it to bind the test case and set up shared helpers:

```php
// tests/Pest.php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature', 'Unit');

// Helper: create an admin user
function asAdmin(): Tests\TestCase
{
    $user = \App\Models\User::factory()->create(['role' => 'admin']);
    return test()->actingAs($user);
}

// Helper: create an editor user
function asEditor(): Tests\TestCase
{
    $user = \App\Models\User::factory()->create(['role' => 'editor']);
    return test()->actingAs($user);
}

// Helper: create a viewer user
function asViewer(): Tests\TestCase
{
    $user = \App\Models\User::factory()->create(['role' => 'viewer']);
    return test()->actingAs($user);
}
```

---

## Test File Structure

```
tests/
├── Pest.php
├── Feature/
│   ├── Api/
│   │   ├── LocationTest.php
│   │   ├── ProjectTest.php
│   │   ├── ProjectAttributeTest.php
│   │   ├── CategoryTest.php
│   │   ├── StatusTest.php
│   │   ├── TeamMemberTest.php
│   │   ├── TeamMemberBioTest.php
│   │   ├── JobTest.php
│   │   ├── TalkTest.php
│   │   ├── AwardTest.php
│   │   ├── JuryTest.php
│   │   ├── NetworkEntryTest.php
│   │   ├── UserTest.php
│   │   ├── ActivityTest.php
│   │   ├── MediaTest.php
│   │   └── PostTest.php
│   └── Policy/
│       ├── LocationPolicyTest.php
│       ├── ProjectPolicyTest.php
│       ├── CategoryPolicyTest.php
│       ├── StatusPolicyTest.php
│       ├── TeamMemberPolicyTest.php
│       ├── JobPolicyTest.php
│       ├── TalkPolicyTest.php
│       ├── AwardPolicyTest.php
│       ├── JuryPolicyTest.php
│       ├── NetworkEntryPolicyTest.php
│       └── UserPolicyTest.php
└── Unit/
    ├── Traits/
    │   ├── HasUuidTest.php
    │   └── SortableTest.php
    ├── Actions/
    │   ├── Location/
    │   │   ├── StoreActionTest.php
    │   │   ├── UpdateActionTest.php
    │   │   ├── DeleteActionTest.php
    │   │   └── ReorderActionTest.php
    │   ├── Project/
    │   │   └── ...
    │   ├── Category/
    │   │   └── ...
    │   ├── Status/
    │   │   └── ...
    │   ├── TeamMember/
    │   │   └── ...
    │   ├── TeamMemberBio/
    │   │   └── ...
    │   ├── Job/
    │   │   └── ...
    │   ├── Talk/
    │   │   └── ...
    │   ├── Award/
    │   │   └── ...
    │   ├── Jury/
    │   │   └── ...
    │   └── NetworkEntry/
    │       └── ...
    └── Models/
        ├── LocationTest.php
        ├── ProjectTest.php
        ├── UserTest.php
        ├── TeamMemberTest.php
        └── ...
```

---

## Factories Required

Every model needs a factory. Create them before writing tests:

```
database/factories/
├── LocationFactory.php
├── ProjectFactory.php
├── CategoryFactory.php
├── StatusFactory.php
├── ProjectAttributeFactory.php
├── TeamMemberFactory.php
├── TeamMemberBioFactory.php
├── JobFactory.php
├── TalkFactory.php
├── AwardFactory.php
├── JuryFactory.php
├── NetworkEntryFactory.php
└── MediaFactory.php
```

User factory already exists (Laravel default).

---

## Unit Tests

### Traits

```php
// tests/Unit/Traits/HasUuidTest.php

use App\Models\Location;

describe('HasUuid', function () {
    it('should auto-generate a uuid on creation', function () {
        $location = Location::factory()->create();

        expect($location->uuid)->not->toBeNull();
        expect(Str::isUuid($location->uuid))->toBeTrue();
    });

    it('should not overwrite an existing uuid', function () {
        $uuid = fake()->uuid();
        $location = Location::factory()->create(['uuid' => $uuid]);

        expect($location->uuid)->toBe($uuid);
    });

    it('should use uuid as route key name', function () {
        $location = new Location();

        expect($location->getRouteKeyName())->toBe('uuid');
    });
});
```

```php
// tests/Unit/Traits/SortableTest.php

use App\Models\Location;

describe('Sortable', function () {
    it('should auto-assign sort_order on creation', function () {
        $first = Location::factory()->create();
        $second = Location::factory()->create();

        expect($first->sort_order)->toBe(1);
        expect($second->sort_order)->toBe(2);
    });

    it('should not overwrite an explicit sort_order', function () {
        $location = Location::factory()->create(['sort_order' => 99]);

        expect($location->sort_order)->toBe(99);
    });
});
```

### Models

```php
// tests/Unit/Models/LocationTest.php

use App\Models\Location;
use App\Models\Project;

describe('Location model', function () {
    it('should soft delete', function () {
        $location = Location::factory()->create();
        $location->delete();

        expect($location->trashed())->toBeTrue();
        expect(Location::count())->toBe(0);
        expect(Location::withTrashed()->count())->toBe(1);
    });

    it('should have many projects', function () {
        $location = Location::factory()->create();
        $project = Project::factory()->create(['location_id' => $location->id]);

        expect($location->projects)->toHaveCount(1);
        expect($location->projects->first()->id)->toBe($project->id);
    });
});
```

```php
// tests/Unit/Models/UserTest.php

use App\Models\User;

describe('User model', function () {
    it('should identify admin role', function () {
        $user = User::factory()->create(['role' => 'admin']);

        expect($user->isAdmin())->toBeTrue();
        expect($user->isEditor())->toBeFalse();
        expect($user->isViewer())->toBeFalse();
    });

    it('should identify editor role', function () {
        $user = User::factory()->create(['role' => 'editor']);

        expect($user->isEditor())->toBeTrue();
        expect($user->isAdmin())->toBeFalse();
    });

    it('should default to viewer role', function () {
        $user = User::factory()->create(['role' => 'viewer']);

        expect($user->isViewer())->toBeTrue();
    });
});
```

### Actions

Each action needs 1-3 tests. Pattern shown with Location; repeat for all 12 entities.

```php
// tests/Unit/Actions/Location/StoreActionTest.php

use App\Actions\Location\StoreAction;

describe('Location StoreAction', function () {
    it('should create a location with a slug', function () {
        $location = (new StoreAction)->execute(['title' => 'Zürich']);

        expect($location)->toBeInstanceOf(\App\Models\Location::class);
        expect($location->title)->toBe('Zürich');
        expect($location->slug)->toBe('zurich');
        expect($location->uuid)->not->toBeNull();
    });
});
```

```php
// tests/Unit/Actions/Location/UpdateActionTest.php

use App\Actions\Location\UpdateAction;
use App\Models\Location;

describe('Location UpdateAction', function () {
    it('should update location fields', function () {
        $location = Location::factory()->create(['title' => 'Old']);
        $updated = (new UpdateAction)->execute($location, ['title' => 'New']);

        expect($updated->title)->toBe('New');
    });
});
```

```php
// tests/Unit/Actions/Location/DeleteActionTest.php

use App\Actions\Location\DeleteAction;
use App\Models\Location;

describe('Location DeleteAction', function () {
    it('should soft delete the location', function () {
        $location = Location::factory()->create();
        (new DeleteAction)->execute($location);

        expect($location->fresh()->trashed())->toBeTrue();
    });
});
```

```php
// tests/Unit/Actions/Location/ReorderActionTest.php

use App\Actions\Location\ReorderAction;
use App\Models\Location;

describe('Location ReorderAction', function () {
    it('should reorder locations by uuid', function () {
        $a = Location::factory()->create();
        $b = Location::factory()->create();

        (new ReorderAction)->execute([
            ['uuid' => $b->uuid, 'sort_order' => 1],
            ['uuid' => $a->uuid, 'sort_order' => 2],
        ]);

        expect($b->fresh()->sort_order)->toBe(1);
        expect($a->fresh()->sort_order)->toBe(2);
    });
});
```

---

## Feature Tests: API Endpoints

### Standard CRUD Entity Pattern

Every standard entity (Location, Category, Status, Talk, Award, Jury, NetworkEntry, Job) follows the same test template. The Location example below is the canonical reference.

```php
// tests/Feature/Api/LocationTest.php

use App\Models\Location;
use App\Models\User;

describe('GET /api/dashboard/locations', function () {
    it('should return all locations ordered by sort_order', function () {
        Location::factory()->create(['title' => 'Berlin', 'sort_order' => 2]);
        Location::factory()->create(['title' => 'Zürich', 'sort_order' => 1]);

        $response = asAdmin()->getJson('/api/dashboard/locations');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.title', 'Zürich');
    });

    it('should require authentication', function () {
        $this->getJson('/api/dashboard/locations')
            ->assertUnauthorized();
    });
});

describe('POST /api/dashboard/locations', function () {
    it('should create a location', function () {
        $response = asAdmin()->postJson('/api/dashboard/locations', [
            'title' => 'Zürich',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Zürich');

        expect(Location::count())->toBe(1);
    });

    it('should reject missing title', function () {
        asAdmin()->postJson('/api/dashboard/locations', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    });

    it('should reject viewers', function () {
        asViewer()->postJson('/api/dashboard/locations', ['title' => 'Test'])
            ->assertForbidden();
    });
});

describe('GET /api/dashboard/locations/{uuid}', function () {
    it('should return a single location', function () {
        $location = Location::factory()->create();

        asAdmin()->getJson("/api/dashboard/locations/{$location->uuid}")
            ->assertOk()
            ->assertJsonPath('data.uuid', $location->uuid);
    });

    it('should return 404 for unknown uuid', function () {
        asAdmin()->getJson('/api/dashboard/locations/nonexistent-uuid')
            ->assertNotFound();
    });
});

describe('PUT /api/dashboard/locations/{uuid}', function () {
    it('should update a location', function () {
        $location = Location::factory()->create(['title' => 'Old']);

        $response = asAdmin()->putJson("/api/dashboard/locations/{$location->uuid}", [
            'title' => 'New',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.title', 'New');
    });
});

describe('DELETE /api/dashboard/locations/{uuid}', function () {
    it('should soft delete a location', function () {
        $location = Location::factory()->create();

        asAdmin()->deleteJson("/api/dashboard/locations/{$location->uuid}")
            ->assertNoContent();

        expect(Location::count())->toBe(0);
        expect(Location::withTrashed()->count())->toBe(1);
    });

    it('should reject editors', function () {
        $location = Location::factory()->create();

        asEditor()->deleteJson("/api/dashboard/locations/{$location->uuid}")
            ->assertForbidden();
    });
});

describe('PATCH /api/dashboard/locations/{uuid}/restore', function () {
    it('should restore a soft-deleted location', function () {
        $location = Location::factory()->create();
        $location->delete();

        asAdmin()->patchJson("/api/dashboard/locations/{$location->uuid}/restore")
            ->assertOk();

        expect($location->fresh()->trashed())->toBeFalse();
    });
});

describe('PATCH /api/dashboard/locations/reorder', function () {
    it('should reorder locations', function () {
        $a = Location::factory()->create(['sort_order' => 1]);
        $b = Location::factory()->create(['sort_order' => 2]);

        asAdmin()->patchJson('/api/dashboard/locations/reorder', [
            'items' => [
                ['uuid' => $b->uuid, 'sort_order' => 1],
                ['uuid' => $a->uuid, 'sort_order' => 2],
            ],
        ])->assertNoContent();

        expect($b->fresh()->sort_order)->toBe(1);
        expect($a->fresh()->sort_order)->toBe(2);
    });
});
```

### Project (Complex Entity)

Projects have filters, relationships, and nested attributes.

```php
// tests/Feature/Api/ProjectTest.php

use App\Models\Category;
use App\Models\Location;
use App\Models\Project;

describe('GET /api/dashboard/projects', function () {
    it('should return projects with relationships loaded', function () {
        $project = Project::factory()->create();

        $response = asAdmin()->getJson('/api/dashboard/projects');

        $response->assertOk()
            ->assertJsonStructure(['data' => [['uuid', 'title', 'attributes', 'media', 'categories', 'statuses', 'location']]]);
    });

    it('should filter by search term', function () {
        Project::factory()->create(['title' => 'Wohnhaus Muster']);
        Project::factory()->create(['title' => 'Bürogebäude Zentral']);

        $response = asAdmin()->getJson('/api/dashboard/projects?search=Wohnhaus');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Wohnhaus Muster');
    });

    it('should filter by location', function () {
        $zurich = Location::factory()->create();
        $berlin = Location::factory()->create();
        Project::factory()->create(['location_id' => $zurich->id]);
        Project::factory()->create(['location_id' => $berlin->id]);

        $response = asAdmin()->getJson("/api/dashboard/projects?location={$zurich->id}");

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    });

    it('should filter by publish status', function () {
        Project::factory()->create(['publish' => true]);
        Project::factory()->create(['publish' => false]);

        $response = asAdmin()->getJson('/api/dashboard/projects?publish=true');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    });

    it('should filter by trashed', function () {
        Project::factory()->create();
        $trashed = Project::factory()->create();
        $trashed->delete();

        $response = asAdmin()->getJson('/api/dashboard/projects?trashed=true');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    });
});

describe('POST /api/dashboard/projects', function () {
    it('should create a project with categories and statuses', function () {
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $response = asAdmin()->postJson('/api/dashboard/projects', [
            'title' => 'Neubau Zürich',
            'slug' => 'neubau-zurich',
            'location_id' => $location->id,
            'categories' => [$category->id],
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Neubau Zürich');
    });
});
```

### Nested Resources: Project Attributes

```php
// tests/Feature/Api/ProjectAttributeTest.php

use App\Models\Project;
use App\Models\ProjectAttribute;

describe('POST /api/dashboard/projects/{project}/attributes', function () {
    it('should create an attribute for a project', function () {
        $project = Project::factory()->create();

        $response = asAdmin()->postJson("/api/dashboard/projects/{$project->uuid}/attributes", [
            'key' => 'Bauherr',
            'value' => 'Muster AG',
        ]);

        $response->assertCreated();
        expect($project->attributes()->count())->toBe(1);
    });
});

describe('PUT /api/dashboard/projects/{project}/attributes/{attribute}', function () {
    it('should update a project attribute', function () {
        $project = Project::factory()->create();
        $attribute = ProjectAttribute::factory()->create(['project_id' => $project->id]);

        $response = asAdmin()->putJson(
            "/api/dashboard/projects/{$project->uuid}/attributes/{$attribute->uuid}",
            ['key' => 'Updated', 'value' => 'New Value']
        );

        $response->assertOk();
        expect($attribute->fresh()->key)->toBe('Updated');
    });
});
```

### Nested Resources: Team Member Bios

```php
// tests/Feature/Api/TeamMemberBioTest.php

use App\Models\TeamMember;
use App\Models\TeamMemberBio;

describe('POST /api/dashboard/team/{teamMember}/cv', function () {
    it('should create a bio entry for a team member', function () {
        $member = TeamMember::factory()->create();

        $response = asAdmin()->postJson("/api/dashboard/team/{$member->uuid}/cv", [
            'period' => '2020-2024',
            'description' => 'Senior Architect',
        ]);

        $response->assertCreated();
        expect($member->bios()->count())->toBe(1);
    });
});
```

### Users (Admin Only)

```php
// tests/Feature/Api/UserTest.php

use App\Models\User;

describe('GET /api/dashboard/users', function () {
    it('should allow admin to list users', function () {
        User::factory()->count(3)->create();

        asAdmin()->getJson('/api/dashboard/users')
            ->assertOk();
    });

    it('should reject editors', function () {
        asEditor()->getJson('/api/dashboard/users')
            ->assertForbidden();
    });

    it('should reject viewers', function () {
        asViewer()->getJson('/api/dashboard/users')
            ->assertForbidden();
    });
});
```

### Activity Log (Admin Only)

```php
// tests/Feature/Api/ActivityTest.php

use App\Models\Location;

describe('GET /api/dashboard/activity', function () {
    it('should return activity log for admin', function () {
        Location::factory()->create(); // triggers activity log

        asAdmin()->getJson('/api/dashboard/activity')
            ->assertOk()
            ->assertJsonStructure(['data']);
    });

    it('should reject non-admin users', function () {
        asEditor()->getJson('/api/dashboard/activity')
            ->assertForbidden();
    });

    it('should filter by subject_type', function () {
        Location::factory()->create();

        asAdmin()->getJson('/api/dashboard/activity?subject_type=App\\Models\\Location')
            ->assertOk();
    });
});
```

---

## Feature Tests: Policies

One test file per policy. Tests all role combinations.

```php
// tests/Feature/Policy/LocationPolicyTest.php

use App\Models\Location;
use App\Models\User;

describe('LocationPolicy', function () {
    it('should allow any authenticated user to view', function () {
        $location = Location::factory()->create();

        asViewer()->getJson("/api/dashboard/locations/{$location->uuid}")
            ->assertOk();
    });

    it('should allow admin to create', function () {
        asAdmin()->postJson('/api/dashboard/locations', ['title' => 'Test'])
            ->assertCreated();
    });

    it('should allow editor to create', function () {
        asEditor()->postJson('/api/dashboard/locations', ['title' => 'Test'])
            ->assertCreated();
    });

    it('should deny viewer from creating', function () {
        asViewer()->postJson('/api/dashboard/locations', ['title' => 'Test'])
            ->assertForbidden();
    });

    it('should allow admin to delete', function () {
        $location = Location::factory()->create();

        asAdmin()->deleteJson("/api/dashboard/locations/{$location->uuid}")
            ->assertNoContent();
    });

    it('should deny editor from deleting', function () {
        $location = Location::factory()->create();

        asEditor()->deleteJson("/api/dashboard/locations/{$location->uuid}")
            ->assertForbidden();
    });

    it('should deny viewer from deleting', function () {
        $location = Location::factory()->create();

        asViewer()->deleteJson("/api/dashboard/locations/{$location->uuid}")
            ->assertForbidden();
    });
});
```

---

## Entity Test Matrix

Every entity requires these test categories. Mark each as done when written.

| Entity | CRUD API | Validation | Authorization | Actions | Model |
|--------|----------|------------|---------------|---------|-------|
| Location | [ ] | [ ] | [ ] | [ ] | [ ] |
| Project | [ ] | [ ] | [ ] | [ ] | [ ] |
| ProjectAttribute | [ ] | [ ] | [ ] | [ ] | [ ] |
| Category | [ ] | [ ] | [ ] | [ ] | [ ] |
| Status | [ ] | [ ] | [ ] | [ ] | [ ] |
| TeamMember | [ ] | [ ] | [ ] | [ ] | [ ] |
| TeamMemberBio | [ ] | [ ] | [ ] | [ ] | [ ] |
| Job | [ ] | [ ] | [ ] | [ ] | [ ] |
| Talk | [ ] | [ ] | [ ] | [ ] | [ ] |
| Award | [ ] | [ ] | [ ] | [ ] | [ ] |
| Jury | [ ] | [ ] | [ ] | [ ] | [ ] |
| NetworkEntry | [ ] | [ ] | [ ] | [ ] | [ ] |
| User | [ ] | [ ] | [ ] | [ ] | [ ] |
| Media | [ ] | [ ] | [ ] | [ ] | [ ] |
| Post | [ ] | [ ] | [ ] | [ ] | [ ] |
| Activity | [ ] | - | [ ] | - | - |

---

## Running Tests

```bash
# Run all tests
./vendor/bin/pest

# Run a single test file
./vendor/bin/pest tests/Feature/Api/LocationTest.php

# Run a specific describe block
./vendor/bin/pest --filter="POST /api/dashboard/locations"

# Run only unit tests
./vendor/bin/pest tests/Unit

# Run only feature tests
./vendor/bin/pest tests/Feature

# Run with coverage (requires Xdebug or PCOV)
./vendor/bin/pest --coverage --min=80

# Run in parallel
./vendor/bin/pest --parallel
```

---

## Implementation Order

1. **Factories** — create all model factories first (prerequisite for every test)
2. **Unit/Traits** — HasUuid, Sortable (quick wins, validate foundation)
3. **Unit/Actions** — StoreAction, UpdateAction, DeleteAction, ReorderAction for each entity
4. **Unit/Models** — relationships, soft deletes, casts
5. **Feature/Api** — full CRUD endpoint tests per entity, starting with Location as template
6. **Feature/Policy** — role-based access for all entities
