<?php

namespace App\Filament\Resources\AimResource\Pages;

use App\Filament\Resources\AimResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAims extends ListRecords
{
    protected static string $resource = AimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
