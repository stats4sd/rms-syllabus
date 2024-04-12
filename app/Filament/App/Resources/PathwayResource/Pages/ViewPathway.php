<?php

namespace App\Filament\App\Resources\PathwayResource\Pages;

use Filament\Actions;
use Filament\Pages\Actions\Action;
use App\Filament\App\Pages\Download;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\Alignment;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\App\Pages\CompetencyFramework;
use App\Filament\App\Resources\PathwayResource;

class ViewPathway extends ViewRecord
{
    protected static string $resource = PathwayResource::class;

    public function getTitle(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
