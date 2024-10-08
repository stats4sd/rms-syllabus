<?php

namespace App\Filament\Admin\Resources\ResearchComponentResource\Pages;

use App\Filament\Admin\Resources\ResearchComponentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResearchComponents extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    
    protected static string $resource = ResearchComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
