<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Staff\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Rimba\Floorplan\Models\Location;
use UnitEnum;

final class FloorPlan extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static string|UnitEnum|null $navigationGroup = 'Resources';

    protected static ?string $navigationLabel = 'Floor Plan';

    protected static ?string $title = 'Floor Plan';

    protected static ?int $navigationSort = 40;

    protected string $view = 'bites::floor-plan';

    public ?int $selectedLocationId = null;

    #[Url]
    public ?string $location = null;

    public function mount(): void
    {
        if ($this->location) {
            $location = Location::query()
                ->with('parent.parent.parent.parent')
                ->get()
                ->first(
                    fn (Location $record): bool => $record->slug === $this->location
                );

            if ($location) {
                $this->selectedLocationId = $location->id;

                return;
            }
        }

        $this->selectedLocationId = Location::query()
            ->whereRaw('json_valid(attributes) = 1')
            ->whereRaw("json_extract(attributes, '$.floorplan_img') IS NOT NULL")
            ->whereRaw("json_extract(attributes, '$.floorplan_img') != ''")
            ->orderBy('name')
            ->value('id');
    }

    public function getFloorplanUrlProperty(): ?string
    {
        $path = data_get(
            $this->selectedLocation?->attributes,
            'floorplan_img'
        );

        return filled($path)
            ? Storage::disk('public')->url($path)
            : null;
    }

    public function selectLocation(int $locationId): void
    {
        $this->selectedLocationId = $locationId;

        $location = Location::find($locationId);

        if ($location) {
            $this->location = $location->slug;
        }

        $this->dispatch(
            'floorplan-location-changed'
        );
    }

    public function getSelectedLocationProperty(): ?Location
    {
        return $this->selectedLocationId ? Location::query()->find($this->selectedLocationId) : null;
    }

    public function table(Table $table): Table
    {
        return $table->query(Location::query()->with('parent'))
            ->columns([
                TextColumn::make('code')
                    ->searchable()->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                TextColumn::make('parent_path')
                    ->label('Parent')
                    ->state(fn (Location $record): ?string => $record->parent_path)
                    ->placeholder('Root'),
                IconColumn::make('attributes.floorplan_img')
                    ->label('Floor Plan')
                    ->boolean(fn ($state): bool => filled($state)),
            ])->recordActions([Action::make('viewFloorplan')
            ->label('View')
            ->icon('heroicon-o-map')
            ->hidden(fn (Location $record): bool => blank(data_get($record->attributes, 'floorplan_img')))
            ->action(
                function (Location $record): void {
                    $this->selectLocation($record->getKey());
                    $this->dispatch('collapse-locations-table');
                }
            )])
            ->recordAction('viewFloorplan')
            ->defaultPaginationPageOption(25)
            ->emptyStateHeading('No locations found');
    }
}
