<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InscritoResource\Pages;
use App\Models\Ingresso;
use App\Models\Inscrito;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class InscritoResource extends Resource
{
    protected static ?string $model = Inscrito::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('evento_id')
                    ->label('Evento')
                    ->relationship('evento','nome_evento')
                    ->preload()
                    ->live()
                    ->required(),
                Forms\Components\Select::make('ingresso_id')
                    ->options(fn(Get $get): Collection => Ingresso::query()
                        ->where('evento_id', $get('evento_id'))
                        ->where('ativo', '=', true)
                        ->pluck('nome', 'id'))
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('data_nascimento')
                    ->required()
                    ->label('Data de Nascimento'),
                PhoneNumber::make('celular')
                    ->mask('(99) 99999-9999'),
                Forms\Components\Select::make('sexo')
                    ->required()
                    ->options([
                        'masculino' => 'Masculino',
                        'feminino' => 'Feminino'
                    ]),
                Forms\Components\Select::make('batizado')
                    ->required()
                    ->boolean(),      
                Forms\Components\Select::make('igreja_id')
                    ->label('Igreja')
                    ->relationship(
                        name: 'igreja',
                        titleAttribute: 'nome',
                        modifyQueryUsing: 
                            fn (Builder $query)=> $query
                                ->where('ativo','=',true)
                                ->orderBy('nome')
                                
                                )
                    ->preload()
                    ->required(),
                // Forms\Components\Textarea::make('observacao')
                //     ->columnSpanFull(),
                Forms\Components\Select::make('tipo_pagamento')
                    ->required()
                    ->options([
                        'pix' => 'PIX',
                        'cartao_credito' => 'CARTÃO DE CRÉDITO'
                    ]),
                Forms\Components\Select::make('situacao_pagamento')
                    ->required()
                    ->options(
                        [
                        'pago' => 'PAGO',
                        'aberto' => 'ABERTO'
                    ])
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('evento.nome_evento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresso.nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresso.custo')
                    ->label('Valor')
                    ->prefix('R$ ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('endereco')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('data_nascimento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('celular')
                    ->searchable(),   
                Tables\Columns\TextColumn::make('sexo')
                    ->searchable()
                    ->sortable(),               
                Tables\Columns\IconColumn::make('batizado')
                    ->searchable()
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('igreja.nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_pagamento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('situacao_pagamento')
                    ->label('Status')
                    ->options([
                        'aberto' => 'Aberto',
                        'pago' => 'Pago',
                    ])
                    ->default('Aberto') // Define o valor padrão se necessário.
                    ->disablePlaceholderSelection()
                    ->disabled(function ($record) {
                        // Desabilita o select se o status for 'pago'
                        return $record->situacao_pagamento === 'pago';
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        // Qualquer lógica adicional após o estado ser atualizado.
                    })
                    ->extraAttributes(function ($record) {
                        return [
                            'class' => $record->situacao_pagamento === 'pago' 
                                ? 'bg-gray-100' 
                                : 'bg-green-500',
                        ];
                    })
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
                // Tables\Filters\Filter::make('observacao')
                //     ->label("InChurch")
                //     ->query(fn (Builder $query): Builder => $query->where('observacao', '=', "inchurch")),
                Tables\Filters\Filter::make('situacao_pagamento')
                    ->form([
                        Checkbox::make('pago'),
                        Checkbox::make('aberto'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['pago'],
                                fn (Builder $query): Builder => $query->where('situacao_pagamento', '=', 'pago')
                            )
                            ->when(
                                $data['aberto'],
                                fn (Builder $query): Builder => $query->where('situacao_pagamento', '=', "aberto")
                                            ->orWhere('situacao_pagamento', '=', null),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Export')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->openUrlInNewTab()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                echo Pdf::loadHTML(
                                    Blade::render('pdf', ['records' => $records])
                                )->stream();
                            }, 'relatorio.pdf');
                        }),
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
