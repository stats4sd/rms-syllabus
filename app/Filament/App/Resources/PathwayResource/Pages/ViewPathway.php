<?php

namespace App\Filament\App\Resources\PathwayResource\Pages;

use Filament\Actions;
use Filament\Pages\Actions\Action;
use App\Filament\App\Pages\Download;
use Filament\Support\Enums\Alignment;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\App\Pages\CompetencyFramework;
use App\Filament\App\Resources\PathwayResource;

class ViewPathway extends ViewRecord
{
    protected static string $resource = PathwayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('competencyFramework')->label('View competency framework')->color('gray')->url(CompetencyFramework::getUrl()),
            Action::make('pathwayPDF')
                ->label('Download my pathway (PDF)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(Download::getUrl()),
        ];
    }

    protected static ?string $title = 'Your pathway';
    protected ?string $subheading = 'Essential Research Methods for Agroecology';

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
