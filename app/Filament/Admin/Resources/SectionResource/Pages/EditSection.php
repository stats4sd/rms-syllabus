<?php

namespace App\Filament\Admin\Resources\SectionResource\Pages;

use App\Filament\Admin\Resources\SectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSection extends EditRecord
{
    protected static string $resource = SectionResource::class;

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

        $translations = $this->record->getTranslations('guidance');

        $data['guidance_en'] = isset($translations['en']) ? $this->record->getTranslation('guidance', 'en') : null;
        $data['guidance_es'] = isset($translations['es']) ? $this->record->getTranslation('guidance', 'es') : null;
        $data['guidance_fr'] = isset($translations['fr']) ? $this->record->getTranslation('guidance', 'fr') : null;

        $translations = $this->record->getTranslations('time_estimate');

        $data['time_estimate_en'] = isset($translations['en']) ? $this->record->getTranslation('time_estimate', 'en') : null;
        $data['time_estimate_es'] = isset($translations['es']) ? $this->record->getTranslation('time_estimate', 'es') : null;
        $data['time_estimate_fr'] = isset($translations['fr']) ? $this->record->getTranslation('time_estimate', 'fr') : null;

        return $data;
    }

    protected function afterSave(): void
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
