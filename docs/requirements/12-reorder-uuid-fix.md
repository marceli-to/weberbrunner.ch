# ReorderAction: Fix `id` → `uuid` Lookup

## Problem

12 of 14 `ReorderAction` classes use `where('id', $item['id'])` to find records, but the corresponding models all have the `HasUuid` trait — which sets `getRouteKeyName()` to `uuid`. The frontend pattern established by Media (the only fully wired reorder flow) sends `uuid`, not `id`.

Only two ReorderActions are correct today:
- **Media** — uses `uuid` (correct, model has `HasUuid`)
- **Post** — uses `id` (correct, model does NOT have `HasUuid`)

## Actions to Fix

Each file below needs `where('id', $item['id'])` changed to `where('uuid', $item['uuid'])`:

| # | File | Model |
|---|------|-------|
| 1 | `app/Actions/Location/ReorderAction.php` | Location |
| 2 | `app/Actions/Category/ReorderAction.php` | Category |
| 3 | `app/Actions/Status/ReorderAction.php` | Status |
| 4 | `app/Actions/Project/ReorderAction.php` | Project |
| 5 | `app/Actions/ProjectAttribute/ReorderAction.php` | ProjectAttribute |
| 6 | `app/Actions/TeamMember/ReorderAction.php` | TeamMember |
| 7 | `app/Actions/TeamMemberBio/ReorderAction.php` | TeamMemberBio |
| 8 | `app/Actions/Job/ReorderAction.php` | Job |
| 9 | `app/Actions/Talk/ReorderAction.php` | Talk |
| 10 | `app/Actions/Award/ReorderAction.php` | Award |
| 11 | `app/Actions/Jury/ReorderAction.php` | Jury |
| 12 | `app/Actions/NetworkEntry/ReorderAction.php` | NetworkEntry |

## Additional Notes

- **No form request validation** exists for these reorder endpoints (only `MediaController` uses a `ReorderMediaRequest`). All other controllers pass `request('items')` directly. Consider adding `Reorder*Request` classes that validate `items.*.uuid` and `items.*.sort_order`.
- **Post stays as-is** — it has no `HasUuid` trait and correctly uses `id`.
- **Reference implementation**: `app/Actions/Media/ReorderAction.php` + `app/Http/Requests/Media/ReorderMediaRequest.php`.
