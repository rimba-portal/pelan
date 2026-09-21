<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Team\Resources\Locations;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\Pages\CreateLocation;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\Pages\EditLocation;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\Pages\ListLocations;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\Pages\ViewLocation;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\RelationManagers\ChildLocationsRelationManager;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\Schemas\LocationForm;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\Schemas\LocationInfolist;
use Rimba\Floorplan\Http\UI\Team\Resources\Locations\Tables\LocationsTable;
use Rimba\Floorplan\Models\Location;
use UnitEnum;

final class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Resources';

    protected static ?string $navigationLabel = 'Locations';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return LocationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LocationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [ChildLocationsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocations::route('/'),
            'create' => CreateLocation::route('/create'),
            'view' => ViewLocation::route('/{record}'),
            'edit' => EditLocation::route('/{record}/edit'),
        ];
    }
}
