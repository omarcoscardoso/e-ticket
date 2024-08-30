<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InscritoResource\Pages;
use App\Filament\Resources\InscritoResource\RelationManagers;
use App\Models\Inscrito;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InscritoResource extends Resource
{
    protected static ?string $model = Inscrito::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('endereco')
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('data_nascimento')
                    ->required()
                    ->label('Data de Nascimento'),
                Forms\Components\TextInput::make('celular')
                    ->required()
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                Forms\Components\Radio::make('sexo')
                    ->required()
                    // ->inline()
                    ->options([
                        'masculiNo' => 'Masculino',
                        'feminino' => 'Feminino'
                    ]),
                Forms\Components\Radio::make('batizado')
                    ->required()
                    // ->inline()
                    ->boolean(),
                Forms\Components\Select::make('igreja')
                    ->required()
                    ->options([
                        'ARARANGUÁ' => 'ARARANGUÁ',
                        'AVENTUREIRO' => 'AVENTUREIRO',
                        '1a BIGUAÇU' => '1a BIGUAÇU',
                        '2a BIGUAÇU' => '2a BIGUAÇU',
                        'BOMBINHAS' => 'BOMBINHAS',
                        'BLUMENAU' => 'BLUMENAU',
                        'CURITIBANOS' => 'CURITIBANOS',
                        'ESTREITO' => 'ESTREITO',
                        'GOVERNADOR CELSO RAMOS' => 'GOVERNADOR CELSO RAMOS',
                        'IPIRANGA' => 'IPIRANGA',
                        'ITAJAÍ' => 'ITAJAÍ',
                        'LAGES' => 'LAGES',
                        'MONTE CARLO' => 'MONTE CARLO',
                        'NAVEGANTES' => 'NAVEGANTES',
                        'PALHOÇA' => 'PALHOÇA',
                        'PALHOÇA AQUARIOS' => 'PALHOÇA AQUARIOS',
                        'PASSO FUNDO' => 'PASSO FUNDO',
                        'PORTO ALEGRE' => 'PORTO ALEGRE',
                        'SANTO ÂNGELO' => 'SANTO ÂNGELO',
                        'SÃO FRANCISCO DO SUL' => 'SÃO FRANCISCO DO SUL',
                        'SÃO JOSÉ' => 'SÃO JOSÉ',
                        'VIAMÃO' => 'VIAMÃO',
                        'VIDEIRA' => 'VIDEIRA',
                    ])
                    ->native(false),
                Forms\Components\Textarea::make('observacao')
                    ->columnSpanFull(),
                Forms\Components\Select::make('tipo_pagamento')
                    ->required()
                    ->options([
                        'pix' => 'PIX',
                        'cartao_credito' => 'CARTÃO DE CRÉDITO'
                    ]),
                Forms\Components\Select::make('situacao_pamento')
                    ->required()
                    ->options([
                        'pago' => 'PAGO',
                        'aberto' => 'ABERTO'
                    ]),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_inscrito')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('endereco')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('data_nascimento')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('celular')
                    ->searchable(),   
                Tables\Columns\TextColumn::make('sexo')
                    ->searchable()
                    ->sortable(),               
                Tables\Columns\TextColumn::make('batizado')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('igreja')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_pagamento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('situacao_pagamento')
                    ->label('Situação')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('observacao')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListInscritos::route('/'),
            'create' => Pages\CreateInscrito::route('/create'),
            'edit' => Pages\EditInscrito::route('/{record}/edit'),
        ];
    }
}
