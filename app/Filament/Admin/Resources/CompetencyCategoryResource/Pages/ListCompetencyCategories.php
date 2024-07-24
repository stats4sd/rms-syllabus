<?php

namespace App\Filament\Admin\Resources\CompetencyCategoryResource\Pages;

use App\Filament\Admin\Resources\CompetencyCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompetencyCategories extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    
    protected static string $resource = CompetencyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
