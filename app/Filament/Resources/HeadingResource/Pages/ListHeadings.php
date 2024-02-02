<?php

namespace App\Filament\Resources\HeadingResource\Pages;

use App\Filament\Resources\HeadingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeadings extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    
    protected static string $resource = HeadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
