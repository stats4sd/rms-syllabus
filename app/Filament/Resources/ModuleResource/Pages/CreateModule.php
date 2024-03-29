<?php

namespace App\Filament\Resources\ModuleResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use App\Filament\Resources\ModuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateModule extends CreateRecord
{
    protected static string $resource = ModuleResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['name'] = 'names added after creation';
        $data['description'] = 'descriptions added after creation';
        $data['slug'] = Str::slug($data['name_en'] ?? $data['name_es'] ?? $data['name_fr'] ?? '');

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->name = '';
        $this->record->description = '';

        if(!is_null($this->data['name_en'])){
            $this->record->setTranslation('name', 'en', $this->data['name_en']);
        }

        if(!is_null($this->data['name_es'])){
            $this->record->setTranslation('name', 'es', $this->data['name_es']);
        }

        if(!is_null($this->data['name_fr'])){
            $this->record->setTranslation('name', 'fr', $this->data['name_fr']);
        }

        if(!is_null($this->data['description_en'])){
            $this->record->setTranslation('description', 'en', $this->data['description_en']);
        }

        if(!is_null($this->data['description_es'])){
            $this->record->setTranslation('description', 'es', $this->data['description_es']);
        }

        if(!is_null($this->data['description_fr'])){
            $this->record->setTranslation('description', 'fr', $this->data['description_fr']);
        }


        $this->record->save();
    }
}
