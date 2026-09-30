<?php

namespace Rimba\Floorplan\Http\UI\Admin\Resources\Locations;

use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LocationResource extends Resource
{
    protected static ?string $model = \Rimba\Floorplan\Models\Location::class;

    protected static string|UnitEnum|null $navigationGroup = 'Floorplan';

    protected static string|BackedEnum|null $navigationIcon = 'bites-s-location';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema { return $schema->components([]); }

    public static function infolist(Schema $schema): Schema { return $schema->components([]); }

    public static function table(Table $table): Table { return $table->columns([]); }

    public static function getRelations(): array 
    { 
        return [ 
            // 
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Rimba\Floorplan\Http\UI\Admin\Resources\Locations\Pages\ListLocations::route('/'),
            // 'create' => \Rimba\Floorplan\Http\UI\Admin\Resources\Locations\Pages\CreateLocation::route('/create'),
            // 'view' => \Rimba\Floorplan\Http\UI\Admin\Resources\Locations\Pages\ViewLocation::route('/{record}'),
            // 'edit' => \Rimba\Floorplan\Http\UI\Admin\Resources\Locations\Pages\EditLocation::route('/{record}/edit'),
            //
        ];
    }
}
