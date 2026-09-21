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

    public function mount(): void
    {
        $this->selectedLocationId = Location::query()->whereNotNull('attributes->floorplan_svg')->orderBy('name')->value('id');
    }

    public function selectLocation(int $locationId): void
    {
        $this->selectedLocationId = $locationId;
        $this->dispatch('floorplan-location-changed');
    }

    public function getSelectedLocationProperty(): ?Location
    {
        return $this->selectedLocationId ? Location::query()->find($this->selectedLocationId) : null;
    }

    public function getFloorplanUrlProperty(): ?string
    {
        $path = data_get($this->selectedLocation?->attributes, 'floorplan_svg');

        return filled($path) ? Storage::disk(config('filesystems.default'))->url($path) : null;
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
                IconColumn::make('attributes.floorplan_svg')
                    ->label('Floor Plan')
                    ->boolean(fn ($state): bool => filled($state)),
            ])->recordActions([Action::make('viewFloorplan')
            ->label('View')
            ->icon('heroicon-o-eye')
            ->hidden(fn (Location $record): bool => blank(data_get($record->attributes, 'floorplan_svg')))
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
