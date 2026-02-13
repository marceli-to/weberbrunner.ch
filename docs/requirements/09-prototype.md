# Prototype: Blog Module

Throwaway module to validate the full stack end-to-end: model, migration, API controller, routes, Vue listing, Vue form, Pinia store. Delete once real modules are implemented.

---

## Goal

Verify the complete workflow:

1. Authenticated user navigates to `/dashboard/blog`
2. Sees a listing of blog posts
3. Can create, edit, delete a post
4. All API calls go through `/api/dashboard/blog/*`
5. Unauthenticated users get redirected to `/login`

---

## Database

### Migration: `create_posts_table`

```php
$table->id();
$table->string('title');
$table->string('slug')->unique();
$table->text('content')->nullable();
$table->boolean('publish')->default(false);
$table->timestamps();
```

No UUID, no soft deletes, no sort_order — keep it minimal.

---

## Model

**`app/Models/Post.php`**

```php
class Post extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'publish'];

    protected $casts = [
        'publish' => 'boolean',
    ];
}
```

---

## API Controller

**`app/Http/Controllers/Api/Dashboard/PostController.php`**

Slim controller, direct Eloquent (no actions/services/resources for prototype).

| Method | Route | Description |
|--------|-------|-------------|
| `index` | `GET /api/dashboard/blog` | List all posts |
| `store` | `POST /api/dashboard/blog` | Create post |
| `show` | `GET /api/dashboard/blog/{post}` | Get single post |
| `update` | `PUT /api/dashboard/blog/{post}` | Update post |
| `destroy` | `DELETE /api/dashboard/blog/{post}` | Delete post |

### Response format

Follow documented convention:

```json
// Collection
{ "data": [ { "id": 1, "title": "...", ... } ] }

// Single
{ "data": { "id": 1, "title": "...", ... } }
```

Use `id` instead of `uuid` for the prototype (no HasUuid trait).

### Validation

Inline in controller (no FormRequest for prototype):

```php
$request->validate([
    'title' => 'required|string|max:255',
    'content' => 'nullable|string',
    'publish' => 'boolean',
]);
```

Slug auto-generated from title via `Str::slug()`.

---

## Routes

### API routes

**`routes/api.php`** (create this file + register in `bootstrap/app.php`)

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Dashboard\PostController;

Route::prefix('dashboard')
    ->middleware(['web', 'auth'])
    ->group(function () {

        Route::controller(PostController::class)
            ->prefix('blog')
            ->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::get('/{post}', 'show');
                Route::put('/{post}', 'update');
                Route::delete('/{post}', 'destroy');
            });

    });
```

**Note:** Uses `web` + `auth` middleware (session-based auth, no Sanctum tokens). The existing axios client sends `X-CSRF-TOKEN` and `withCredentials: true`.

### Dashboard SPA route

Already handled by the catch-all in `routes/web.php`:

```php
Route::get('/dashboard/{any?}', ...)->where('any', '.*');
```

No changes needed — `/dashboard/blog` is already caught.

---

## Vue: Router

**`resources/js/app/router/index.js`** — add routes:

```javascript
import BlogIndex from '../views/blog/Index.vue'
import BlogForm from '../views/blog/Form.vue'

// Add to routes array:
{
    path: '/dashboard/blog',
    name: 'blog.index',
    component: BlogIndex,
},
{
    path: '/dashboard/blog/create',
    name: 'blog.create',
    component: BlogForm,
},
{
    path: '/dashboard/blog/:id/edit',
    name: 'blog.edit',
    component: BlogForm,
},
```

---

## Vue: API Service

**`resources/js/app/api/blog.js`**

```javascript
import api from './axios'

export default {
    index: () => api.get('/blog'),
    show: (id) => api.get(`/blog/${id}`),
    store: (data) => api.post('/blog', data),
    update: (id, data) => api.put(`/blog/${id}`, data),
    destroy: (id) => api.delete(`/blog/${id}`),
}
```

---

## Vue: Pinia Store

**`resources/js/app/stores/blog.js`**

```javascript
import { defineStore } from 'pinia'
import blogApi from '../api/blog'

export const useBlogStore = defineStore('blog', {
    state: () => ({
        posts: [],
        current: null,
        loading: false,
    }),
    actions: {
        async fetchPosts() { ... },
        async fetchPost(id) { ... },
        async savePost(data, id = null) { ... },
        async deletePost(id) { ... },
    },
})
```

---

## Vue: Views

### Listing — `resources/js/app/views/blog/Index.vue`

- Table with columns: Title, Status (publish), Created, Actions
- "New Post" button → navigates to `/dashboard/blog/create`
- Each row has Edit / Delete actions
- Uses `useBlogStore` to fetch and display posts
- Loading state while fetching

### Form — `resources/js/app/views/blog/Form.vue`

- Shared for create + edit (detect via route param `:id`)
- Fields: Title (text input), Content (textarea), Publish (checkbox)
- Save button → calls store or update
- Cancel button → navigates back to listing
- Validation errors displayed inline

---

## Files to Create

```
database/migrations/YYYY_MM_DD_create_posts_table.php
app/Models/Post.php
app/Http/Controllers/Api/Dashboard/PostController.php
routes/api.php
resources/js/app/api/blog.js
resources/js/app/stores/blog.js
resources/js/app/views/blog/Index.vue
resources/js/app/views/blog/Form.vue
```

## Files to Modify

```
bootstrap/app.php              → register api.php routes
resources/js/app/router/index.js → add blog routes
```

---

## Cleanup

Once real modules are implemented, delete:

- Migration + run `php artisan migrate:rollback`
- `app/Models/Post.php`
- `app/Http/Controllers/Api/Dashboard/PostController.php`
- `resources/js/app/api/blog.js`
- `resources/js/app/stores/blog.js`
- `resources/js/app/views/blog/`
- Blog routes from `routes/api.php`
- Blog routes from router
- This document
