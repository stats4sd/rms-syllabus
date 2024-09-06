<?php

namespace App\Filament\Admin\Resources\ModuleResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\ModuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateModule extends CreateRecord
{
    protected static string $resource = ModuleResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['name'] = 'names added after creation';
        $data['description'] = 'descriptions added after creation';
        $data['time_estimate'] = 'time estimates added after creation';
        $data['guidance'] = 'guidance added after creation';
        $data['why'] = 'why added after creation';
        $data['learning_outcome'] = 'learning_outcome added after creation';
        $data['slug'] = Str::slug($data['name_en'] ?? $data['name_es'] ?? $data['name_fr'] ?? '');

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->name = '';
        $this->record->description = '';
        $this->record->time_estimate = '';
        $this->record->guidance = '';
        $this->record->why = '';
        $this->record->learning_outcome = '';

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

        if(!is_null($this->data['time_estimate_en'])){
            $this->record->setTranslation('time_estimate', 'en', $this->data['time_estimate_en']);
        }

        if(!is_null($this->data['time_estimate_es'])){
            $this->record->setTranslation('time_estimate', 'es', $this->data['time_estimate_es']);
        }

        if(!is_null($this->data['time_estimate_fr'])){
            $this->record->setTranslation('time_estimate', 'fr', $this->data['time_estimate_fr']);
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

        if(!is_null($this->data['why_en'])){
            $this->record->setTranslation('why', 'en', $this->data['why_en']);
        }

        if(!is_null($this->data['why_es'])){
            $this->record->setTranslation('why', 'es', $this->data['why_es']);
        }

        if(!is_null($this->data['why_fr'])){
            $this->record->setTranslation('why', 'fr', $this->data['why_fr']);
        }

        if(!is_null($this->data['learning_outcome_en'])){
            $this->record->setTranslation('learning_outcome', 'en', $this->data['learning_outcome_en']);
        }

        if(!is_null($this->data['learning_outcome_es'])){
            $this->record->setTranslation('learning_outcome', 'es', $this->data['learning_outcome_es']);
        }

        if(!is_null($this->data['learning_outcome_fr'])){
            $this->record->setTranslation('learning_outcome', 'fr', $this->data['learning_outcome_fr']);
        }

        $this->record->save();
    }
}
