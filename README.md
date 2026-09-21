# Rimba Pelan Simple Filament v5 UI

Includes:
- Team `LocationResource` with parent and child locations and SVG upload stored at `attributes.floorplan_svg`.
- Staff `FloorPlan` page with zoomable/pannable SVG image and clickable location table.
- Shared `LocationAssignmentsRelationManager` for any model using `HasLocationAssignments`.

## Required Location model correction
The existing model method is named `childrens()`. Rename it or add this canonical relationship:

```php
public function children(): HasMany
{
    return $this->hasMany(Location::class, 'parent_id');
}
```

The existing `attributes` cast to array is used, so no migration is required for the SVG path.

## Register package views
In `FloorplanServiceProvider::bootPackage()`:

```php
$this->loadViewsFrom(__DIR__ . '/../resources/views', 'floorplan');
```

## Shared relation manager integration
In an assignable model:

```php
use Rimba\Floorplan\Models\Concerns\HasLocationAssignments;
use HasLocationAssignments;
```

In its Filament Resource:

```php
public static function getRelations(): array
{
    return [
        LocationAssignmentsRelationManager::class,
    ];
}
```

## Storage
Run `php artisan storage:link` when using the public local disk. Ensure the configured disk can return a browser-accessible URL.

## SVG safety
The Staff viewer renders the SVG through an `<img>` element rather than injecting raw SVG markup. Keep server-side MIME validation and apply your normal upload security controls.
