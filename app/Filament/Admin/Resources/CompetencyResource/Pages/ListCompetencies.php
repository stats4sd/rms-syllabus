<?php

namespace App\Filament\Admin\Resources\CompetencyResource\Pages;

use App\Filament\Admin\Resources\CompetencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompetencies extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    
    protected static string $resource = CompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
