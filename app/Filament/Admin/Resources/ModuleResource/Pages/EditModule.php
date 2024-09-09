<?php

namespace App\Filament\Admin\Resources\ModuleResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\ModuleResource;

class EditModule extends EditRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $translations = $this->record->getTranslations('name');

        $data['name_en'] = isset($translations['en']) ? $this->record->getTranslation('name', 'en') : null;
        $data['name_es'] = isset($translations['es']) ? $this->record->getTranslation('name', 'es') : null;
        $data['name_fr'] = isset($translations['fr']) ? $this->record->getTranslation('name', 'fr') : null;

        $translations = $this->record->getTranslations('description');

        $data['description_en'] = isset($translations['en']) ? $this->record->getTranslation('description', 'en') : null;
        $data['description_es'] = isset($translations['es']) ? $this->record->getTranslation('description', 'es') : null;
        $data['description_fr'] = isset($translations['fr']) ? $this->record->getTranslation('description', 'fr') : null;

        $translations = $this->record->getTranslations('time_estimate');

        $data['time_estimate_en'] = isset($translations['en']) ? $this->record->getTranslation('time_estimate', 'en') : null;
        $data['time_estimate_es'] = isset($translations['es']) ? $this->record->getTranslation('time_estimate', 'es') : null;
        $data['time_estimate_fr'] = isset($translations['fr']) ? $this->record->getTranslation('time_estimate', 'fr') : null;

        $translations = $this->record->getTranslations('guidance');

        $data['guidance_en'] = isset($translations['en']) ? $this->record->getTranslation('guidance', 'en') : null;
        $data['guidance_es'] = isset($translations['es']) ? $this->record->getTranslation('guidance', 'es') : null;
        $data['guidance_fr'] = isset($translations['fr']) ? $this->record->getTranslation('guidance', 'fr') : null;

        $translations = $this->record->getTranslations('why');

        $data['why_en'] = isset($translations['en']) ? $this->record->getTranslation('why', 'en') : null;
        $data['why_es'] = isset($translations['es']) ? $this->record->getTranslation('why', 'es') : null;
        $data['why_fr'] = isset($translations['fr']) ? $this->record->getTranslation('why', 'fr') : null;

        $translations = $this->record->getTranslations('learning_outcome');

        $data['learning_outcome_en'] = isset($translations['en']) ? $this->record->getTranslation('learning_outcome', 'en') : null;
        $data['learning_outcome_es'] = isset($translations['es']) ? $this->record->getTranslation('learning_outcome', 'es') : null;
        $data['learning_outcome_fr'] = isset($translations['fr']) ? $this->record->getTranslation('learning_outcome', 'fr') : null;

        return $data;
    }

    protected function afterSave(): void
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

        $this->record['slug'] = Str::slug($this->data['name_en'] ?? $this->data['name_es'] ?? $this->data['name_fr'] ?? '');

        $this->record->save();
    }
}
