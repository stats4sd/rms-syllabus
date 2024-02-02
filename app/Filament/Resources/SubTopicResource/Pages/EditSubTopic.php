<?php

namespace App\Filament\Resources\SubTopicResource\Pages;

use App\Filament\Resources\SubTopicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubTopic extends EditRecord
{
    protected static string $resource = SubTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
