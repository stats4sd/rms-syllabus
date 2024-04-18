<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Resources\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateActivity extends CreateRecord
{
    protected static string $resource = ActivityResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['name'] = 'names added after creation';
        $data['description'] = 'descriptions added after creation';
        $data['trove_id'] = 'trove ids added after creation';
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->name = '';
        $this->record->description = '';
        $this->record->trove_id = '';

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

        if(!is_null($this->data['trove_id_en'])){
            $this->record->setTranslation('trove_id', 'en', $this->data['trove_id_en']);
        }

        if(!is_null($this->data['trove_id_es'])){
            $this->record->setTranslation('trove_id', 'es', $this->data['trove_id_es']);
        }

        if(!is_null($this->data['trove_id_fr'])){
            $this->record->setTranslation('trove_id', 'fr', $this->data['trove_id_fr']);
        }

        $this->record->save();
    }
}
