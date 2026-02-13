# Testing

## Overview

Testing strategy for the application:
- Unit tests and integration tests
- Pest for PHP/Laravel, Vitest for Vue/TypeScript
- 80%+ code coverage requirement
- Tests run manually by developers

---

## Frameworks

### Backend: Pest

[Pest](https://pestphp.com/) for PHP testing:

```bash
composer require pestphp/pest --dev
composer require pestphp/pest-plugin-laravel --dev
./vendor/bin/pest --init
```

### Frontend: Vitest

[Vitest](https://vitest.dev/) for Vue/TypeScript testing:

```bash
npm install -D vitest @vue/test-utils jsdom
```

```typescript
// vitest.config.ts
import { defineConfig } from 'vitest/config'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  test: {
    environment: 'jsdom',
    globals: true,
  },
})
```

---

## Test File Structure

All tests are located in separate `tests/` directories:

```
tests/
├── Feature/           # PHP integration tests
│   ├── Api/
│   │   ├── ProjectTest.php
│   │   ├── TeamMemberTest.php
│   │   └── AuthTest.php
│   └── ...
├── Unit/              # PHP unit tests
│   ├── Models/
│   ├── Services/
│   └── ...
└── ...

resources/js/tests/    # Vue/TypeScript tests
├── components/
│   ├── Button.test.ts
│   └── Modal.test.ts
├── composables/
│   └── useAuth.test.ts
└── ...
```

---

## Naming Conventions

Use descriptive "it should..." style for all test methods:

### Pest (PHP)

```php
describe('ProjectController', function () {
    it('should return paginated projects', function () {
        // ...
    });

    it('should create a project with valid data', function () {
        // ...
    });

    it('should reject unauthorized users', function () {
        // ...
    });
});
```

### Vitest (TypeScript)

```typescript
describe('Button', () => {
  it('should render with default props', () => {
    // ...
  })

  it('should emit click event when clicked', () => {
    // ...
  })

  it('should display loading state', () => {
    // ...
  })
})
```

---

## Coverage Requirements

Minimum 80% code coverage is required.

### Running Coverage

```bash
# PHP
./vendor/bin/pest --coverage --min=80

# TypeScript
npx vitest --coverage
```

### Coverage Configuration

```php
// phpunit.xml
<coverage>
    <include>
        <directory suffix=".php">./app</directory>
    </include>
    <exclude>
        <directory>./app/Console</directory>
        <directory>./app/Exceptions</directory>
    </exclude>
</coverage>
```

```typescript
// vitest.config.ts
export default defineConfig({
  test: {
    coverage: {
      provider: 'v8',
      reporter: ['text', 'html'],
      exclude: ['node_modules/', 'tests/'],
      thresholds: {
        lines: 80,
        branches: 80,
        functions: 80,
        statements: 80,
      },
    },
  },
})
```

---

## Test Areas

### API Endpoints

All API endpoints must have tests:

```php
describe('POST /api/dashboard/projects', function () {
    it('should create a project with valid data', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/dashboard/projects', [
                'title' => 'Test Project',
                'slug' => 'test-project',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['uuid', 'title', 'slug']]);
    });

    it('should reject invalid data', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/dashboard/projects', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    });
});
```

### Business Logic

Core domain logic and services:

```php
describe('ProjectService', function () {
    it('should publish a project', function () {
        $project = Project::factory()->unpublished()->create();
        $service = new ProjectService();

        $service->publish($project);

        expect($project->fresh()->publish)->toBeTrue();
    });
});
```

### Vue Components

Frontend components with user interactions:

```typescript
import { mount } from '@vue/test-utils'
import ProjectCard from '@/components/ProjectCard.vue'

describe('ProjectCard', () => {
  it('should render project title', () => {
    const wrapper = mount(ProjectCard, {
      props: {
        project: { title: 'Test Project', slug: 'test' },
      },
    })

    expect(wrapper.text()).toContain('Test Project')
  })

  it('should emit edit event when edit button clicked', async () => {
    const wrapper = mount(ProjectCard, {
      props: {
        project: { title: 'Test', slug: 'test', can: { update: true } },
      },
    })

    await wrapper.find('[data-test="edit-button"]').trigger('click')

    expect(wrapper.emitted('edit')).toBeTruthy()
  })
})
```

### Database Operations

Models, migrations, and queries:

```php
describe('Project Model', function () {
    it('should soft delete a project', function () {
        $project = Project::factory()->create();

        $project->delete();

        expect($project->trashed())->toBeTrue();
        expect(Project::count())->toBe(0);
        expect(Project::withTrashed()->count())->toBe(1);
    });

    it('should order by sort_order', function () {
        Project::factory()->create(['sort_order' => 2]);
        Project::factory()->create(['sort_order' => 1]);

        $projects = Project::ordered()->get();

        expect($projects->first()->sort_order)->toBe(1);
    });
});
```

---

## Mocking

External services must always be mocked in tests.

### HTTP Requests

```php
use Illuminate\Support\Facades\Http;

it('should handle external API response', function () {
    Http::fake([
        'api.example.com/*' => Http::response(['data' => 'mocked'], 200),
    ]);

    $service = new ExternalApiService();
    $result = $service->fetch();

    expect($result)->toBe(['data' => 'mocked']);
});
```

### File Storage

```php
use Illuminate\Support\Facades\Storage;

it('should upload a file', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('photo.jpg');

    $this->actingAs($user)
        ->postJson('/api/dashboard/media', ['file' => $file]);

    Storage::disk('public')->assertExists('media/' . $file->hashName());
});
```

### Vue Composables

```typescript
import { vi } from 'vitest'

vi.mock('@/api/projects', () => ({
  fetchProjects: vi.fn().mockResolvedValue([
    { id: 1, title: 'Mocked Project' },
  ]),
}))
```

---

## Running Tests

### Commands

```bash
# Run all PHP tests
./vendor/bin/pest

# Run specific PHP test file
./vendor/bin/pest tests/Feature/Api/ProjectTest.php

# Run PHP tests with coverage
./vendor/bin/pest --coverage

# Run all Vue/TypeScript tests
npx vitest

# Run tests in watch mode
npx vitest --watch

# Run with coverage
npx vitest --coverage
```

---

## Summary

| Aspect | Requirement |
|--------|-------------|
| Frameworks | Pest (PHP), Vitest (Vue/TS) |
| Test Types | Unit, Integration |
| Coverage | 80% minimum |
| Execution | Manual |
| File Location | Separate tests/ folders |
| Naming | Descriptive "it should..." |
| Mocking | Always mock external services |
