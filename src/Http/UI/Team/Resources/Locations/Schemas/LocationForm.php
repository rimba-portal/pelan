<?php

declare(strict_types=1);

namespace Rimba\Floorplan\Http\UI\Team\Resources\Locations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Rimba\Floorplan\Models\Location;

final class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Location')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('code')->maxLength(100),
                TextInput::make('type')->required()->maxLength(100)->datalist(['enterprise', 'site', 'building', 'floor', 'area', 'room', 'store', 'workspace']),
                Select::make('parent_id')->label('Parent location')->relationship('parent', 'name')->searchable()->preload()->getOptionLabelFromRecordUsing(fn (Location $record): string => trim(($record->code ? $record->code.' - ' : '').$record->name))->modifyQueryUsing(fn (Builder $query, ?Location $record): Builder => $query->when($record, fn (Builder $q) => $q->whereKeyNot($record->getKey()))),
                Textarea::make('description')->columnSpanFull()->rows(3),
            ])->columns(2),
            Section::make('Floor plan')->description('Upload an SVG for this location. Staff will select the location from the directory and view this file.')->schema([
                FileUpload::make('attributes.floorplan_svg')->label('SVG floor plan')->disk(config('filesystems.default'))->directory('floorplans')->acceptedFileTypes(['image/svg+xml'])->maxSize(5120)->downloadable()->openable()->preserveFilenames(),
            ]),
            Section::make('Additional attributes')->schema([KeyValue::make('attributes.metadata')->label('Metadata')])->collapsed(),
        ]);
    }
}
