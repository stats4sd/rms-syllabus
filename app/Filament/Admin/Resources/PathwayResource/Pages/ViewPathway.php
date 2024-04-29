<?php

namespace App\Filament\Admin\Resources\PathwayResource\Pages;

use App\Filament\Admin\Resources\PathwayResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPathway extends ViewRecord
{
    protected static string $resource = PathwayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
