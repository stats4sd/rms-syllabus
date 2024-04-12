<?php

namespace App\Filament\App\Resources\ModuleResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\App\Resources\ModuleResource;

class ViewModule extends ViewRecord
{
    protected static string $resource = ModuleResource::class;

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

}
