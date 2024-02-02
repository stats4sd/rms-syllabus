<?php

namespace App\Filament\Resources\SubTopicResource\Pages;

use App\Filament\Resources\SubTopicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubTopics extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    
    protected static string $resource = SubTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
