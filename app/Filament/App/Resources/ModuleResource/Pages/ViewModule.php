<?php

namespace App\Filament\App\Resources\ModuleResource\Pages;

use Filament\Actions;
use App\Filament\App\Traits\HasParentResource;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\App\Resources\ModuleResource;

class ViewModule extends ViewRecord
{
    use HasParentResource;

    protected static string $resource = ModuleResource::class;

    protected static string $view = 'filament.app.resources.modules.module_header';

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
