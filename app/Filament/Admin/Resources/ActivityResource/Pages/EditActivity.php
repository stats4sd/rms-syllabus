<?php

namespace App\Filament\Admin\Resources\ActivityResource\Pages;

use App\Filament\Admin\Resources\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivity extends EditRecord
{
    protected static string $resource = ActivityResource::class;

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

        $translations = $this->record->getTranslations('trove_id');

        $data['trove_id_en'] = isset($translations['en']) ? $this->record->getTranslation('trove_id', 'en') : null;
        $data['trove_id_es'] = isset($translations['es']) ? $this->record->getTranslation('trove_id', 'es') : null;
        $data['trove_id_fr'] = isset($translations['fr']) ? $this->record->getTranslation('trove_id', 'fr') : null;

        return $data;
    }

    protected function afterSave(): void
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
