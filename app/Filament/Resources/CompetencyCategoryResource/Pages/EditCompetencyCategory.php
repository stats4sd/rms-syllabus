<?php

namespace App\Filament\Resources\CompetencyCategoryResource\Pages;

use App\Filament\Resources\CompetencyCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompetencyCategory extends EditRecord
{
    protected static string $resource = CompetencyCategoryResource::class;

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

        return $data;
    }

    protected function afterSave(): void
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

    protected function getRedirectUrl(): string 
    {
        return $this->getResource()::getUrl('index');
    }
}
