<?php

namespace App\Filament\Resources\AimResource\Pages;

use App\Filament\Resources\AimResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAim extends EditRecord
{
    protected static string $resource = AimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
