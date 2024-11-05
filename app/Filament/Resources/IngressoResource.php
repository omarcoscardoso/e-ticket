<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngressoResource\Pages;
use App\Filament\Resources\IngressoResource\RelationManagers;
use App\Models\Ingresso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Money;


class IngressoResource extends Resource
{
    protected static ?string $model = Ingresso::class;
    protected static ?string $navigationGroup = 'Configuração';
    protected static ?string $navigationIcon = 'bx-wallet-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('evento_id')
                    ->label('Evento')
                    ->relationship('evento','nome_evento')
                    ->required(),
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Money::make('custo')
                    ->prefix('R$')
                    ->default('0,00')
                    ->required(),
                // Forms\Components\TextInput::make('custo')
                    // ->numeric(),
                Forms\Components\Toggle::make('ativo')
                    ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('evento.nome_evento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('custo')
                    ->prefix('R$')
                    ->suffix(',00')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('ativo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageIngressos::route('/'),
        ];
    }
}
