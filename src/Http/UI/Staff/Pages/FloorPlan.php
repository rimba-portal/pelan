<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Staff\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Rimba\Floorplan\Models\Location;

class FloorPlan extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'bites-location';

    protected static ?string $navigationLabel = 'Floor Plan';

    protected static ?string $title = 'Floor Plan';

    protected static ?string $slug = 'floor-plan';

    protected string $view = 'bites::floor-plan';

    /**
     * Table filter controlled by floor-plan-tabs.blade.php.
     *
     * Supported values:
     * - all
     * - with-plans
     */
    public string $planFilter = 'all';

    /**
     * Selected location slug/path persisted in the URL.
     */
    #[Url]
    public ?string $location = null;

    /**
     * Currently selected location.
     */
    public ?int $selectedLocationId = null;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    */

    public static function canAccess(): bool
    {
        return auth()->user()?->staff?->hasAnyPermission([
            'view.floorplan.location',
        ]) ?? true;
    }

    public static function canManage(): bool
    {
        return auth()->user()?->staff?->hasAnyPermission([
            'create.floorplan.location',
            'update.floorplan.location',
        ]) ?? false;
    }

    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        /*
         * Restore a location supplied through the URL.
         */
        if (filled($this->location)) {
            $location = Location::query()
                ->with('parent.parent.parent.parent')
                ->get()
                ->first(
                    fn (Location $record): bool => $record->slug === $this->location
                );

            if ($location) {
                $this->selectedLocationId = $location->getKey();

                return;
            }
        }

        /*
         * Otherwise, select the first available location
         * containing a floor plan.
         */
        $this->selectedLocationId = Location::query()
            ->whereRaw('json_valid(attributes) = 1')
            ->whereRaw(
                "json_extract(attributes, '$.floorplan_img') IS NOT NULL"
            )
            ->whereRaw(
                "json_extract(attributes, '$.floorplan_img') != ''"
            )
            ->orderBy('name')
            ->value('id');

        if ($this->selectedLocationId) {
            $location = Location::query()
                ->find($this->selectedLocationId);

            $this->location = $location?->slug;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Plan Filter
    |--------------------------------------------------------------------------
    */

    public function setPlanFilter(string $filter): void
    {
        if (! in_array($filter, [
            'all',
            'with-plans',
        ], true)) {
            return;
        }

        $this->planFilter = $filter;
    }

    /*
    |--------------------------------------------------------------------------
    | Selected Location
    |--------------------------------------------------------------------------
    */

    public function getSelectedLocationProperty(): ?Location
    {
        if (! $this->selectedLocationId) {
            return null;
        }

        return Location::query()
            ->with('parent.parent.parent.parent')
            ->find($this->selectedLocationId);
    }

    /*
    |--------------------------------------------------------------------------
    | Floor Plan URL
    |--------------------------------------------------------------------------
    */

    public function getFloorplanUrlProperty(): ?string
    {
        $path = data_get(
            $this->selectedLocation?->attributes,
            'floorplan_img'
        );

        if (blank($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /*
    |--------------------------------------------------------------------------
    | Location Selection
    |--------------------------------------------------------------------------
    */

    public function selectLocation(int $locationId): void
    {
        $location = Location::query()
            ->find($locationId);

        if (! $location) {
            return;
        }

        $this->selectedLocationId = $location->getKey();

        $this->location = $location->slug;

        /*
         * Reset the Alpine zoom/pan viewer.
         */
        $this->dispatch(
            'floorplan-location-changed'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => Location::query()
                    ->with('parent')
                    ->when(
                        $this->planFilter === 'with-plans',
                        fn (Builder $query): Builder => $this->applyWithPlansScope($query)
                    )
            )
            ->defaultSort('name')
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('parent_path')
                    ->label('Parent')
                    ->state(
                        fn (Location $record): ?string => $record->parent_path
                    )
                    ->placeholder('Root'),

                IconColumn::make('attributes.floorplan_img')
                    ->label('Floor Plan')
                    ->boolean(
                        fn ($state): bool => filled($state)
                    ),
            ])
            ->recordActions([
                /*
                 * Staff action:
                 * display the selected floor plan.
                 */
                Action::make('viewFloorplan')
                    ->label('View')
                    ->icon('heroicon-o-map')
                    ->hidden(
                        fn (Location $record): bool => ! $this->hasFloorPlan($record)
                    )
                    ->action(
                        function (Location $record): void {
                            $this->selectLocation(
                                $record->getKey()
                            );

                            $this->dispatch(
                                'collapse-locations-table'
                            );
                        }
                    ),
            ])
            /*
             * Row click displays the floor plan only when
             * one exists.
             */
            ->recordAction(
                fn (Location $record): ?string => $this->hasFloorPlan($record)
                    ? 'viewFloorplan'
                    : null
            )
            ->defaultPaginationPageOption(25)
            ->emptyStateHeading('No locations found');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Helpers
    |--------------------------------------------------------------------------
    */

    protected function applyWithPlansScope(
        Builder $query,
    ): Builder {
        return $query
            ->whereRaw('json_valid(attributes) = 1')
            ->whereRaw(
                "json_extract(attributes, '$.floorplan_img') IS NOT NULL"
            )
            ->whereRaw(
                "json_extract(attributes, '$.floorplan_img') != ''"
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Floor Plan Helpers
    |--------------------------------------------------------------------------
    */

    protected function hasFloorPlan(
        Location $location,
    ): bool {
        return filled(
            data_get(
                $location->attributes,
                'floorplan_img'
            )
        );
    }
}
