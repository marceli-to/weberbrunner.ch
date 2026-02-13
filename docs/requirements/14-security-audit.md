# Security Audit

## Overview

Full security audit for the weberbrunner.ch application. Covers authentication, authorization, input validation, data protection, infrastructure, and dependency security.

**Audit date:** 2026-02-12

---

## 1. Authentication

### Session Management

- [x] Verify session configuration: secure cookies, `HttpOnly`, `SameSite` attributes
- [x] Check session lifetime and idle timeout settings
- [x] Confirm session regeneration on login
- [x] Verify session invalidation on logout
- [x] Check password hashing and validation rules
- [x] Verify rate limiting on login endpoint

### Findings

#### OK: Login/logout flow is secure

Laravel Breeze provides a solid foundation:
- **Session regeneration on login:** `$request->session()->regenerate()` in `AuthenticatedSessionController@store`
- **Session invalidation on logout:** `Auth::guard('web')->logout()` + `$request->session()->invalidate()` + `$request->session()->regenerateToken()`
- **Rate limiting:** 5 attempts per email+IP, 60-second lockout, fires `Lockout` event
- **Password hashing:** Bcrypt with 12 rounds (`BCRYPT_ROUNDS=12`), password cast as `'hashed'`
- **Password rules:** `Rules\Password::defaults()` (8+ chars, mixed case, numbers, symbols)
- **Remember token:** Regenerated on password reset (prevents session hijacking)

#### CRITICAL (production): Secure cookie flag not set

`SESSION_SECURE_COOKIE` is not configured in `.env`. Session cookies can be transmitted over HTTP, enabling man-in-the-middle attacks.

**Fix:** Add to production `.env`:
```
SESSION_SECURE_COOKIE=true
```

#### HIGH (production): Session encryption disabled

`SESSION_ENCRYPT=false` — session data is stored in plaintext in the database. If the database is compromised, all session contents are exposed.

**Fix:** Add to production `.env`:
```
SESSION_ENCRYPT=true
```

#### OK: Session config defaults

| Setting | Value | Status |
|---------|-------|--------|
| Driver | database | OK |
| Lifetime | 120 minutes | OK |
| HttpOnly | true | OK |
| SameSite | lax | OK |
| Encryption | false | Fix for production |
| Secure cookie | not set | Fix for production |

#### MEDIUM: Rate limit lockout too short

Login throttle is 60 seconds after 5 failed attempts. Consider increasing to 300-600 seconds for production.

---

## 2. Authorization

### Role-Based Access Control

- [x] Audit all policies for all three roles (admin, editor, viewer)
- [x] Test policy enforcement in every controller
- [x] Check for mass-assignment vulnerabilities
- [x] Verify route protection
- [x] Audit Form Request authorize() methods

### Findings

#### CRITICAL: Policies exist but are NEVER enforced in controllers

11 policy classes are defined with correct role logic, but **zero** API controllers call `$this->authorize()`, `Gate::allows()`, or any policy check. Every controller method executes without any authorization beyond the `auth` middleware.

**Impact:** Any authenticated user (including viewers) can:
- Create, update, and delete ALL resources (projects, locations, team members, etc.)
- Restore soft-deleted records
- Upload and delete media
- View the complete activity log

**Affected controllers (all 16):**

| Controller | Methods without authorization |
|------------|------------------------------|
| ProjectController | index, store, show, update, destroy, restore, reorder |
| LocationController | index, store, show, update, destroy, restore, reorder |
| CategoryController | index, store, show, update, destroy, restore, reorder |
| StatusController | index, store, show, update, destroy, restore, reorder |
| TeamMemberController | index, store, show, update, destroy, restore, reorder |
| TeamMemberBioController | index, store, update, destroy, reorder |
| JobController | index, store, show, update, destroy, restore, reorder |
| TalkController | index, store, show, update, destroy, restore, reorder |
| AwardController | index, store, show, update, destroy, restore, reorder |
| JuryController | index, store, show, update, destroy, restore, reorder |
| NetworkEntryController | index, store, show, update, destroy, restore, reorder |
| PostController | index, store, show, update, destroy, reorder |
| MediaController | upload, update, destroy, reorder, teaser |
| ProjectAttributeController | index, store, update, destroy, reorder |
| UserController | index, store, show, update, destroy, restore |
| ActivityController | index |

**Fix:** Add `$this->authorize()` to every controller method:

```php
// Example for store method
public function store(StoreProjectRequest $request)
{
	$this->authorize('create', Project::class);
	// ... existing code
}

// Example for update method
public function update(UpdateProjectRequest $request, Project $project)
{
	$this->authorize('update', $project);
	// ... existing code
}
```

#### CRITICAL: `role` field is in User `$fillable` — privilege escalation

`app/Models/User.php` includes `'role'` in the `$fillable` array. Combined with the missing authorization checks, any authenticated user can:

1. Create a new admin account via `POST /api/dashboard/users` with `role: 'admin'`
2. Escalate their own role via `PUT /api/dashboard/users/{uuid}` with `role: 'admin'`
3. Demote other admins to viewer

**Fix:** Remove `role` from `$fillable`:

```php
protected $fillable = [
	'firstname',
	'name',
	'email',
	'password',
	// 'role' — REMOVED: set via dedicated admin action only
];
```

#### HIGH: All Form Requests return `authorize() => true`

All 30+ Form Request classes unconditionally authorize:

```php
public function authorize(): bool
{
	return true;
}
```

This provides no second layer of defense. If a policy check is ever skipped, there's no fallback.

**Fix:** Implement authorization in Form Requests:

```php
public function authorize(): bool
{
	return $this->user()->isAdmin() || $this->user()->isEditor();
}
```

#### OK: Route authentication

All `/api/dashboard/*` routes correctly require `['web', 'auth']` middleware. No unprotected dashboard routes exist.

#### OK: Policy permission matrix

The policies themselves are well-structured:

| Action | admin | editor | viewer |
|--------|-------|--------|--------|
| viewAny / view | yes | yes | yes |
| create | yes | yes | no |
| update | yes | yes | no |
| delete | yes | no | no |
| restore | yes | no | no |
| forceDelete | yes | no | no |

---

## 3. Input Validation & Injection Prevention

### SQL Injection

- [x] Audit all raw queries for parameter binding
- [x] Check search/filter functionality
- [x] Verify Eloquent used consistently

### Findings

#### OK: No SQL injection vulnerabilities found

Only one raw SQL query exists in the entire application:

```php
// app/Livewire/Works.php:152
$query->orderByRaw("CASE WHEN title LIKE ? THEN 0 ELSE 1 END", [$likeTerm])
```

This is properly parameterized with `?` placeholder. All other queries use Eloquent's query builder with automatic parameter binding.

Search/filter in `ProjectController@index` uses safe query builder methods:
```php
$query->where('title', 'like', '%' . request('search') . '%')
```

### Cross-Site Scripting (XSS)

- [x] Audit all Blade templates for unescaped output
- [x] Check Vue templates for v-html
- [x] Audit rich text fields

#### OK: No XSS vulnerabilities found

- **0 instances** of `{!! !!}` (unescaped Blade output) across 74 templates
- **0 instances** of `v-html` across 97 Vue components
- All output uses safe `{{ }}` Blade escaping or Vue's auto-escaped `{{ }}`

#### NOTE: TipTap rich text editor

The blog form uses TipTap for rich text editing. TipTap output is HTML stored in the `content` field. When this content is rendered on the public site, it must be sanitized. Verify the rendering path does not use `{!! !!}` or `v-html` with unsanitized content.

### Cross-Site Request Forgery (CSRF)

- [x] Verify CSRF middleware on web routes
- [x] Check API route CSRF protection
- [x] Verify Axios CSRF token headers

#### OK: CSRF protection via web middleware

API routes use `['web', 'auth']` middleware, which includes Laravel's `ValidateCsrfToken`. Blade forms use `@csrf`. The `X-XSRF-TOKEN` cookie is handled via `.htaccess` header forwarding.

#### MEDIUM: Verify Axios sends CSRF token

`resources/js/bootstrap.js` sets `X-Requested-With: XMLHttpRequest` but does not explicitly set `X-CSRF-TOKEN`. Laravel's `web` middleware should handle this via the `XSRF-TOKEN` cookie, but this should be verified with actual requests.

### Request Validation

- [x] Audit all Form Request classes
- [x] Check reorder endpoint validation
- [x] Verify file upload validation

#### HIGH: Reorder endpoints have NO validation

13 out of 14 reorder controller methods pass `request('items')` directly to action classes without any validation:

```php
// Example: PostController::reorder()
public function reorder()
{
	(new ReorderPostAction)->execute(request('items'));
}
```

Only `MediaController::reorder()` uses a Form Request (`ReorderMediaRequest`), but even that lacks range validation on `sort_order`.

**Vulnerabilities:**
- No UUID validation — arbitrary IDs could be submitted
- No sort_order type/range validation — negative, zero, or INT_MAX values accepted
- No ownership validation — items from other entities could be reordered

**Fix:** Create a reorder Form Request for every entity:

```php
public function rules(): array
{
	return [
		'items' => 'required|array|min:1',
		'items.*.uuid' => 'required|string|exists:projects,uuid',
		'items.*.sort_order' => 'required|integer|min:0|max:9999',
	];
}
```

#### MEDIUM: Missing max length on text fields

Several Form Requests accept `nullable|string` without max length:
- `StoreProjectRequest` / `UpdateProjectRequest`: `description` field
- `StorePostRequest` / `UpdatePostRequest`: `content` field

**Fix:** Add `max:65000` (or appropriate limit) to prevent oversized payloads.

#### OK: File upload validation

`UploadMediaRequest` validates: `required|file|mimes:jpg,jpeg,png,webp,gif|max:51200` (50 MB).

---

## 4. File Upload Security

### Upload Handling

- [x] Check server-side MIME validation
- [x] Audit filename sanitization
- [x] Verify storage location
- [x] Audit Glide image processing

### Findings

#### CRITICAL: Glide has no URL signature validation

`app/Http/Controllers/ImageController.php` creates a Glide server without a `security_key`:

```php
$this->server = ServerFactory::create([
	'source' => storage_path('app/public'),
	'cache' => storage_path('app/.glide-cache'),
	'driver' => 'imagick',
	// NO security_key!
]);
```

The route uses `where('path', '.*')` allowing any path. Without URL signing:
- **Path traversal:** An attacker could request `/img/../../.env` to read sensitive files
- **Parameter tampering:** Arbitrary image dimensions (`?w=10000&h=10000`) can cause DoS via CPU/memory exhaustion
- **Cache poisoning:** Unlimited parameter combinations fill disk with cached variants

**Fix:** Enable Glide URL signatures:

```php
$this->server = ServerFactory::create([
	'source' => storage_path('app/public'),
	'cache' => storage_path('app/.glide-cache'),
	'driver' => 'imagick',
	'security_key' => config('app.key'),
]);

// Validate signature in show method:
try {
	SignatureFactory::create(config('app.key'))->validateRequest($path, $request->all());
} catch (SignatureException $e) {
	abort(403);
}
```

#### HIGH: No server-side MIME type verification in UploadAction

`app/Actions/Media/UploadAction.php` calls `$file->getMimeType()` to record the MIME type but does not independently verify the file's actual content. While Laravel's `mimes` validation rule uses `finfo`, additional server-side checks in the action would provide defense in depth:

- SVG files with embedded JavaScript could bypass if validation is tricked
- Double-extension files (`shell.php.jpg`) could be problematic
- Polyglot files that are valid images but also valid PHP could execute if the web server is misconfigured

**Fix:** Add explicit content validation in `UploadAction`:

```php
// Verify this is actually an image
if (!@getimagesize($file->getRealPath())) {
	throw new \InvalidArgumentException('File is not a valid image');
}

// Reject SVG content hidden in other formats
$content = file_get_contents($file->getRealPath(), false, null, 0, 1024);
if (str_contains($content, '<svg') || str_contains($content, '<?php')) {
	throw new \InvalidArgumentException('Invalid file content');
}
```

#### MEDIUM: AttachAction filename not sanitized

`app/Actions/Media/AttachAction.php` uses `pathinfo($filename, PATHINFO_FILENAME)` without slug conversion, unlike `UploadAction` which uses `Str::slug()`. This inconsistency could allow directory traversal via `../` in filenames.

**Fix:** Use `Str::slug()` consistently:
```php
$name = Str::slug(pathinfo($filename, PATHINFO_FILENAME));
```

#### MEDIUM: No upload rate limiting

The upload endpoint has no rate limiting. An authenticated user could exhaust disk space by uploading files repeatedly.

**Fix:** Add throttle middleware:
```php
Route::post('/upload', 'upload')->middleware('throttle:20,1');
```

#### MEDIUM: No temp file cleanup

Files uploaded to `storage/app/public/temp/` that are never attached remain indefinitely.

**Fix:** Create a scheduled cleanup command.

#### OK: Storage location

Files are stored in `storage/app/public/` which is symlinked to `public/storage`. Direct PHP execution is prevented by Laravel's front controller routing.

#### OK: Glide caching

- Server-side cache in `storage/app/.glide-cache/`
- Browser cache with `Cache-Control: max-age=31536000, public`
- ImageMagick driver (fast and secure)

---

## 5. Data Protection

### Sensitive Data Exposure

- [x] Check API responses for internal IDs and sensitive data
- [x] Verify User model $hidden array
- [x] Audit .env and secrets management
- [x] Check activity log data exposure

### Findings

#### OK: API resources properly configured

- All resources expose UUIDs (not internal integer IDs)
- User model has `$hidden = ['password', 'remember_token']`
- No passwords, tokens, or secrets in API responses
- UserResource does not expose password hash or remember_token

**NOTE:** All resources expose both `id` and `uuid`. While the `id` is not a security risk per se, best practice is to only expose `uuid` to prevent enumeration.

#### OK: Secrets management

- `.env` is in `.gitignore`
- `.env.example` contains no real credentials
- No hardcoded secrets found in PHP source code
- All config files use `env()` functions

#### MEDIUM: Activity log logs all fields

All 14 models use `LogOptions::defaults()->logAll()->logOnlyDirty()`. This logs every field change including potentially sensitive data. While passwords are hashed before logging, the activity log stores a complete record of all changes.

The activity log is accessible to any authenticated user via `ActivityController@index` (no authorization check — see Section 2).

**Fix:** Use `->logOnly([...])` with explicit field lists, and restrict ActivityController to admin-only.

#### CRITICAL (production): APP_DEBUG=true

Already noted in authentication section. Stack traces expose file paths, database structure, and variable values.

---

## 6. HTTP Security Headers

- [x] Audit middleware configuration
- [x] Check for security headers
- [x] Audit CORS configuration
- [x] Check HTTPS enforcement

### Findings

#### CRITICAL: No security headers configured

`bootstrap/app.php` has an empty middleware configuration:

```php
->withMiddleware(function (Middleware $middleware): void {
	//
})
```

No security headers are set anywhere in the application:

| Header | Status | Risk |
|--------|--------|------|
| Content-Security-Policy | MISSING | XSS attack vector open |
| X-Content-Type-Options | MISSING | MIME sniffing attacks |
| X-Frame-Options | MISSING | Clickjacking attacks |
| Strict-Transport-Security | MISSING | HTTP downgrade attacks |
| Referrer-Policy | MISSING | Referrer leakage |
| Permissions-Policy | MISSING | Unrestricted browser APIs |

**Fix:** Create a `SecurityHeaders` middleware:

```php
// app/Http/Middleware/SecurityHeaders.php
public function handle(Request $request, Closure $next)
{
	$response = $next($request);

	$response->headers->set('X-Content-Type-Options', 'nosniff');
	$response->headers->set('X-Frame-Options', 'SAMEORIGIN');
	$response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
	$response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
	$response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
	$response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");

	return $response;
}
```

Register in `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware): void {
	$middleware->append(\App\Http\Middleware\SecurityHeaders::class);
	$middleware->append(\Illuminate\Http\Middleware\FrameGuard::class);
})
```

#### HIGH: CORS allows all origins

Using Laravel's default CORS config:

```php
'allowed_origins' => ['*'],     // ANY domain
'allowed_methods' => ['*'],     // ANY method
'allowed_headers' => ['*'],     // ANY header
```

**Fix:** Restrict to application domain:

```php
// config/cors.php
'allowed_origins' => [env('APP_URL')],
'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
'allowed_headers' => ['Content-Type', 'Authorization', 'X-CSRF-TOKEN', 'X-Requested-With'],
```

#### HIGH (production): No HTTPS enforcement

`APP_URL=http://weberbrunner.ch.test` uses HTTP. No HTTPS redirect exists in middleware or `.htaccess`.

**Fix for production:**
- Set `APP_URL=https://weberbrunner.ch`
- Add HTTPS redirect in `.htaccess` or middleware
- Set `SESSION_SECURE_COOKIE=true`

---

## 7. Dependency Security

### PHP Dependencies

- [x] Run `composer audit`
- [x] Check Laravel version

#### MEDIUM: 1 vulnerability found

```
Package:   psy/psysh
Severity:  medium
CVE:       CVE-2026-25129
Title:     PsySH has Local Privilege Escalation via CWD .psysh.php auto-load
Affected:  <=0.11.22|>=0.12.0,<=0.12.18
```

This is a **dev dependency** (used by `php artisan tinker`). Not present in production if `--no-dev` is used during deployment.

**Fix:** `composer update psy/psysh`

#### OK: Laravel version

Laravel Framework v12.46.0 — current and supported release.

### JavaScript Dependencies

- [x] Run `npm audit`

#### HIGH: 1 vulnerability found

```
Package:   axios <=1.13.4
Severity:  high
Title:     Axios Vulnerable to DoS via __proto__ Key in mergeConfig
Advisory:  GHSA-43fc-jf86-j433
```

Axios is used in both the dashboard (API client) and bootstrap.js.

**Fix:** `npm audit fix` or update axios to latest version.

---

## 8. Infrastructure Security

### Production Configuration Checklist

These items require server-level verification:

- [ ] HTTPS enforced with TLS 1.2+
- [ ] Server version headers hidden (`Server`, `X-Powered-By`)
- [ ] `.env`, `.git`, `storage/`, `vendor/` not web-accessible
- [ ] OPcache enabled
- [ ] `expose_php = Off`
- [ ] `display_errors = Off`
- [ ] `disable_functions` includes dangerous functions
- [ ] Log files rotated and access-controlled
- [ ] Database connections use SSL/TLS
- [ ] Database user has minimal required privileges

### OK: .htaccess configuration

- Directory listing disabled (`Options -Indexes`)
- All requests routed through front controller
- Authorization and XSRF headers properly forwarded

---

## 9. Application-Specific Concerns

### Activity Log

- **No authorization:** Any authenticated user can view the full audit trail (CRITICAL — see Section 2)
- **No deletion protection:** No explicit protection against deleting activity log entries via API (mitigated: no delete route exists)
- **Verbose logging:** `logAll()` captures every field change

### Soft Deletes

- **Restore not restricted:** Any authenticated user can restore soft-deleted records (CRITICAL — see Section 2)
- **OK:** Normal API queries exclude soft-deleted records by default (Eloquent handles this)

### Reorder Endpoints

- **No validation:** 13 of 14 reorder endpoints accept raw `request('items')` without validation (HIGH — see Section 3)
- **No ownership checks:** Items from any entity could be reordered
- **No range validation:** sort_order accepts any integer value

---

## Summary of Findings

### By Severity

| # | Severity | Area | Finding |
|---|----------|------|---------|
| 1 | CRITICAL | Authorization | Policies never enforced — all 16 controllers lack authorization |
| 2 | CRITICAL | Authorization | `role` in User `$fillable` — any user can escalate to admin |
| 3 | CRITICAL | File Upload | Glide has no URL signing — path traversal + DoS risk |
| 4 | CRITICAL | Headers | No security headers (CSP, X-Frame-Options, HSTS, etc.) |
| 5 | CRITICAL | Infra | `APP_DEBUG=true` in production exposes sensitive data |
| 6 | CRITICAL | Session | `SESSION_SECURE_COOKIE` not set — MITM risk |
| 7 | HIGH | Authorization | All 30+ Form Requests return `authorize() => true` |
| 8 | HIGH | Validation | 13 reorder endpoints have zero input validation |
| 9 | HIGH | File Upload | No server-side MIME content verification |
| 10 | HIGH | Headers | CORS allows all origins/methods/headers |
| 11 | HIGH | Infra | No HTTPS enforcement |
| 12 | HIGH | Session | Session encryption disabled |
| 13 | HIGH | Dependencies | Axios DoS vulnerability (npm) |
| 14 | MEDIUM | Auth | Rate limit lockout too short (60 seconds) |
| 15 | MEDIUM | CSRF | Verify Axios sends CSRF token in API requests |
| 16 | MEDIUM | Validation | Missing max length on text/content fields |
| 17 | MEDIUM | File Upload | AttachAction filename not sanitized |
| 18 | MEDIUM | File Upload | No upload rate limiting |
| 19 | MEDIUM | File Upload | No temp file cleanup |
| 20 | MEDIUM | Data | Activity log accessible to all users, logs all fields |
| 21 | MEDIUM | Dependencies | psysh privilege escalation (dev only) |
| 22 | LOW | Data | API resources expose both `id` and `uuid` |

### What's Working Well

- SQL injection protection: Proper use of Eloquent and parameterized queries throughout
- XSS prevention: No unescaped Blade output, no v-html in Vue components
- Password security: Bcrypt 12 rounds, proper hashing cast, hidden attributes
- Login flow: Session regeneration, invalidation on logout, rate limiting
- Route authentication: All dashboard/API routes require auth middleware
- Secrets management: No hardcoded secrets, .env properly gitignored
- Policy structure: Well-designed role matrix (admin/editor/viewer)
- File validation: Proper MIME whitelist and size limits on upload

---

## Priority Implementation Order

### Phase 1 — CRITICAL (fix before any production use)

1. **Add authorization to all 16 API controllers** — add `$this->authorize()` calls using existing policies
2. **Remove `role` from User `$fillable`** — prevent privilege escalation
3. **Add Glide URL signing** — prevent path traversal and parameter tampering
4. **Create SecurityHeaders middleware** — CSP, X-Frame-Options, HSTS, etc.
5. **Production .env** — `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, `SESSION_ENCRYPT=true`

### Phase 2 — HIGH (fix before production launch)

6. **Add Form Request validation to all 13 reorder endpoints**
7. **Implement Form Request authorize() methods** — use policies as second layer
8. **Restrict CORS to application domain**
9. **Enforce HTTPS** — redirect middleware or .htaccess rule
10. **Fix axios vulnerability** — `npm audit fix`
11. **Add server-side MIME verification** in UploadAction

### Phase 3 — MEDIUM (fix shortly after launch)

12. **Restrict ActivityController to admin-only**
13. **Add max length validation to text fields**
14. **Sanitize filenames in AttachAction**
15. **Add upload rate limiting**
16. **Create temp file cleanup command**
17. **Switch activity log to `logOnly([...])`**
18. **Increase login rate limit lockout to 300+ seconds**
19. **Update psysh** — `composer update psy/psysh`

---

## Tools

| Tool | Purpose |
|------|---------|
| `composer audit` | PHP dependency vulnerability scan |
| `npm audit` | JS dependency vulnerability scan |
| OWASP ZAP | Automated web vulnerability scanning |
| Burp Suite | Manual penetration testing proxy |
| Mozilla Observatory | HTTP header security analysis |
| SSL Labs | TLS configuration testing |
| PHPStan | Static analysis for code quality |
| Pest (existing) | Automated security-focused test cases |
