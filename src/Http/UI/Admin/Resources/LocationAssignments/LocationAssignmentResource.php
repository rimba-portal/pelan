<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments\Pages\ListLocationAssignments;
use Rimba\Floorplan\Models\LocationAssignment;
use UnitEnum;

class LocationAssignmentResource extends Resource
{
    protected static ?string $model = LocationAssignment::class;

    protected static string|UnitEnum|null $navigationGroup = 'Floorplan';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'type';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocationAssignments::route('/'),
            // 'create' => \Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments\Pages\CreateLocationAssignment::route('/create'),
            // 'view' => \Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments\Pages\ViewLocationAssignment::route('/{record}'),
            // 'edit' => \Rimba\Floorplan\Http\UI\Admin\Resources\LocationAssignments\Pages\EditLocationAssignment::route('/{record}/edit'),
            //
        ];
    }
}
