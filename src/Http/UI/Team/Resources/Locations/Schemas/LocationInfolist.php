<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Team\Resources\Locations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class LocationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Location')->schema([
                TextEntry::make('name'),
                TextEntry::make('code')
                    ->placeholder('None'),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('parent.name')
                    ->label('Parent')
                    ->placeholder('Root location'),
                TextEntry::make('description')
                    ->columnSpanFull()
                    ->placeholder('No description'),
                TextEntry::make('attributes.floorplan_img')
                    ->label('Floor plan')
                    ->placeholder('No SVG uploaded'),
            ])->columns(2),
        ]);
    }
}
