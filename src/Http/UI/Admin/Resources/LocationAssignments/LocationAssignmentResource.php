<?php

namespace Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments;

use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LocationAssignmentResource extends Resource
{
    protected static ?string $model = \Rimba\Floorplan\Models\LocationAssignment::class;

    protected static string|UnitEnum|null $navigationGroup = 'Floorplan';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'type';

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
            'index' => \Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments\Pages\ListLocationAssignments::route('/'),
            // 'create' => \Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments\Pages\CreateLocationAssignment::route('/create'),
            // 'view' => \Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments\Pages\ViewLocationAssignment::route('/{record}'),
            // 'edit' => \Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments\Pages\EditLocationAssignment::route('/{record}/edit'),
            //
        ];
    }
}
