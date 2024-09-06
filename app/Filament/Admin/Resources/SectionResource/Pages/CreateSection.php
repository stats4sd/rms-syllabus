<?php

namespace App\Filament\Admin\Resources\SectionResource\Pages;

use App\Filament\Admin\Resources\SectionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSection extends CreateRecord
{
    protected static string $resource = SectionResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['name'] = 'names added after creation';
        $data['description'] = 'descriptions added after creation';
        $data['guidance'] = 'guidance added after creation';
        $data['time_estimate'] = 'time estimates added after creation';
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->name = '';
        $this->record->description = '';
        $this->record->guidance = '';
        $this->record->time_estimate = '';

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

        if(!is_null($this->data['guidance_en'])){
            $this->record->setTranslation('guidance', 'en', $this->data['guidance_en']);
        }

        if(!is_null($this->data['guidance_es'])){
            $this->record->setTranslation('guidance', 'es', $this->data['guidance_es']);
        }

        if(!is_null($this->data['guidance_fr'])){
            $this->record->setTranslation('guidance', 'fr', $this->data['guidance_fr']);
        }

        if(!is_null($this->data['time_estimate_en'])){
            $this->record->setTranslation('time_estimate', 'en', $this->data['time_estimate_en']);
        }

        if(!is_null($this->data['time_estimate_es'])){
            $this->record->setTranslation('time_estimate', 'es', $this->data['time_estimate_es']);
        }

        if(!is_null($this->data['time_estimate_fr'])){
            $this->record->setTranslation('time_estimate', 'fr', $this->data['time_estimate_fr']);
        }

        $this->record->save();
    }

}
