<?php

namespace App\Filament\Resources\PathwayResource\Pages;

use App\Filament\Resources\PathwayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPathways extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    
    protected static string $resource = PathwayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
