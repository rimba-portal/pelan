<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Team\Resources\Locations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Rimba\Floorplan\Models\Location;

final class LocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table->defaultSort('name')->columns([
            TextColumn::make('code')->searchable()->sortable(), TextColumn::make('name')->searchable()->sortable(), TextColumn::make('type')->badge()->searchable()->sortable(), TextColumn::make('parent.name')->label('Parent')->searchable()->placeholder('Root'), IconColumn::make('attributes.floorplan_svg')->label('SVG')->boolean(fn ($state): bool => filled($state)), TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])->filters([SelectFilter::make('type')->options(fn (): array => Location::query()->distinct()->orderBy('type')->pluck('type', 'type')->all())])->recordActions([ViewAction::make(), EditAction::make()])->toolbarActions([CreateAction::make(), BulkActionGroup::make([DeleteBulkAction::make()])])->persistFiltersInSession();
    }
}
