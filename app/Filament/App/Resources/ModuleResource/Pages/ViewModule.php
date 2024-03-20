<?php

namespace App\Filament\App\Resources\ModuleResource\Pages;

use App\Filament\App\Resources\ModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewModule extends ViewRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected static ?string $title = 'Module';

}
