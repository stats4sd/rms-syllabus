<?php

namespace App\Filament\Resources\PathwayResource\Pages;

use App\Filament\Resources\PathwayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPathway extends EditRecord
{
    protected static string $resource = PathwayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
