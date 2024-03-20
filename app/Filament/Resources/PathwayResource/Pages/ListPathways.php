<?php

namespace App\Filament\Resources\PathwayResource\Pages;

use App\Filament\Resources\PathwayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPathways extends ListRecords
{
    protected static string $resource = PathwayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
