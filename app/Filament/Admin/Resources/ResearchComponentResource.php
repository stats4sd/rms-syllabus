<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ResearchComponent;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Concerns\Translatable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\ResearchComponentResource\Pages;
use App\Filament\Admin\Resources\ResearchComponentResource\RelationManagers;

class ResearchComponentResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = ResearchComponent::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Research Component Name')
                            ->columns(3)
                            ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                            ->icon('heroicon-m-chat-bubble-oval-left-ellipsis')
                            ->iconColor('primary')
                            ->description('A sensible, descriptive name for the research component. Ideally not too long!')
                            ->schema([
                                Forms\Components\TextInput::make('name')->hiddenOn(['edit', 'create']),
                                Forms\Components\Textarea::make('name_en')
                                                ->label('English')
                                                ->rows(2)
                                                ->regex('/^(?=.*[^\W_])[^\n]+$/')
                                                ->requiredWithoutAll('name_es, name_fr')
                                                ->validationMessages(['regex' => 'Name cannot only contain special characters',
                                                                      'required_without_all' => 'Enter the name in at least one language']),
                                Forms\Components\Textarea::make('name_es')
                                                ->label('Spanish')
                                                ->rows(2)
                                                ->regex('/^(?=.*[^\W_])[^\n]+$/')
                                                ->requiredWithoutAll('name_en, name_fr')
                                                ->validationMessages(['regex' => 'Name cannot only contain special characters',
                                                                      'required_without_all' => 'Enter the name in at least one language']),
                                Forms\Components\Textarea::make('name_fr')
                                                ->label('French')
                                                ->rows(2)
                                                ->regex('/^(?=.*[^\W_])[^\n]+$/')
                                                ->requiredWithoutAll('name_es, name_en')
                                                ->validationMessages(['regex' => 'Name cannot only contain special characters',
                                                                      'required_without_all' => 'Enter the name in at least one language']),
                            ]),

                Forms\Components\Section::make('Research Component Description')
                            ->icon('heroicon-m-document-text')
                            ->iconColor('primary')
                            ->extraAttributes(['style' => 'background-color: #E6E6E6;'])
                            ->description('A brief explanation of the research component.')
                            ->columns(3)
                            ->schema([
                                Forms\Components\TextInput::make('description')->hiddenOn(['edit', 'create']),
                                Forms\Components\Textarea::make('description_en')
                                                ->label('English')
                                                ->rows(6),
                                                // ->requiredWithoutAll('description_es, description_fr')
                                                // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                                Forms\Components\Textarea::make('description_es')
                                                ->label('Spanish')
                                                ->rows(6),
                                                // ->requiredWithoutAll('description_en, description_fr')
                                                // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                                Forms\Components\Textarea::make('description_fr')
                                                ->label('French')
                                                ->rows(6),
                                                // ->requiredWithoutAll('description_es, description_en')
                                                // ->validationMessages(['required_without_all' => 'Enter the description in at least one language']),
                            ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('description')->wrap(),
                Tables\Columns\TextColumn::make('modules_count')
                                            ->counts('modules')
                                            ->label('# Modules')
                                            ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResearchComponents::route('/'),
            'create' => Pages\CreateResearchComponent::route('/create'),
            'edit' => Pages\EditResearchComponent::route('/{record}/edit'),
        ];
    }
}
