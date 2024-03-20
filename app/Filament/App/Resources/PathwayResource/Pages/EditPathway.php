<?php

namespace App\Filament\App\Resources\PathwayResource\Pages;

use App\Filament\App\Resources\PathwayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPathway extends EditRecord
{
    protected static string $resource = PathwayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
