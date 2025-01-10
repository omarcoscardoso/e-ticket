<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InscritoResource\Pages;
use App\Models\Ingresso;
use App\Models\Inscrito;
use App\Models\Pagamento;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
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
use Leandrocfe\FilamentPtbrFormFields\Document;

class InscritoResource extends Resource
{
    protected static ?string $model = Inscrito::class;
    protected static ?string $navigationGroup = 'Controle de Inscrições';

    protected static ?string $navigationIcon = 'bx-group';

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
                    ->mask('(99) 99999-9999')
                    ->required(),
                Document::make('cpf')
                    ->cpf()
                    ->required(),
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
                Forms\Components\Select::make('tamanho_camiseta')
                    ->required()
                    ->options([
                        'PP' => 'PP',
                        'P' => 'P',
                        'M' => 'M',
                        'G' => 'G',
                        'GG' => 'GG',
                        'XG' => 'XG',
                    ])
                    ->placeholder('Selecione o tamanho'),
                Forms\Components\Select::make('tipo_pagamento')
                    ->required()
                    ->options([
                        'pix' => 'PIX',
                        'cartao_credito' => 'CARTÃO DE CRÉDITO',
                        'local' => 'PAGAR NO DIA DO EVENTO',
                        'isento' => 'Isento'
                    ]),

                Forms\Components\Select::make('status')
                    ->label('Status do Pagamento')
                    ->options([
                        'PAID' => 'Pago',
                        'IN_ANALYSIS' => 'Em Análise',
                        'DECLINED' => 'Recusado',
                        'CANCELED' => 'Cancelado',
                        'WAITING' => 'Aguardando',
                        'FREE' => 'Isento',
                    ])
                    ->formatStateUsing(function ($record) {
                        return $record?->pagamento?->status;
                    })                    
                    ->placeholder('Selecione o status'),
                    
            ])->columns(4);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpf')
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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tipo_pagamento')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pix' => 'PIX',
                            'cartao_credito' => 'CARTÃO DE CRÉDITO',
                            'local' => 'LOCAL',
                            'isento' => 'Isento',
                            default => $state,
                        };
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tamanho_camiseta')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('pagamento.status')
                    ->label('Status')    
                    ->colors([
                        'success' => 'PAID', 
                        'info' => 'IN_ANALYSIS', 
                        'gray' => 'DECLINED', 
                        'danger' => 'CANCELED', 
                        'warning' => 'WAITING',
                        'primary' => 'FREE',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'PAID' => 'Pago',
                            'IN_ANALYSIS' => 'Em Análise',
                            'DECLINED' => 'Recusado',
                            'CANCELED' => 'Cancelado',
                            'WAITING' => 'Aguardando',
                            'FREE' => 'Isento',
                            default => $state,
                        };
                    })
                    ->searchable()
                    ->sortable(),                
                Tables\Columns\TextColumn::make('pagamento.order_id')
                    ->label('order_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),    
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
                Tables\Filters\Filter::make('inscritos.batizado')
                    ->form([
                        Checkbox::make('Batizados'),
                        Checkbox::make('Não Batizados'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['Batizados'],
                                fn (Builder $query): Builder => $query->where('batizado', true)
                            )
                            ->when(
                                $data['Não Batizados'],
                                fn (Builder $query): Builder => $query->where('batizado', false)
                            );
                    }),
                Tables\Filters\Filter::make('pagamento.status')
                    ->form([
                        Checkbox::make('pago'),
                        Checkbox::make('isento'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereHas('pagamento', function (Builder $query) use ($data) {
                            return $query
                                ->when(
                                    $data['pago'],
                                    fn (Builder $query): Builder => $query->where('status', '=', 'PAID')
                                )
                                ->when(
                                    $data['isento'],
                                    fn (Builder $query): Builder => $query->where('status', '=', 'FREE')
                                );
                        });
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\ViewAction::make()->iconButton(),                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Export')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->openUrlInNewTab()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                // Mapeamento de status
                                $statusMap = [
                                    'PAID' => 'Pago',
                                    'IN_ANALYSIS' => 'Em Análise',
                                    'DECLINED' => 'Recusado',
                                    'CANCELED' => 'Cancelado',
                                    'WAITING' => 'Aguardando',
                                    'FREE' => 'Isento',
                                ];
                                
                                $records = $records->map(function ($record) use ($statusMap) {
                                    // Pega o status do pagamento ou 'Sem status' se não houver
                                    $status = $record->pagamento ? $record->pagamento->status : null;
            
                                    // Traduz o status
                                    $record->status_pagamento = $status ? $statusMap[$status] ?? 'Desconhecido' : 'Sem status';
                                    
                                    return $record;
                                });
                                
                                echo Pdf::loadHTML(
                                    Blade::render('pdf', ['records' => $records])
                                )->stream();
                            }, 'relatorio.pdf');
                        }),
                    Tables\Actions\BulkAction::make('Atualizar Status Pagamento')
                        ->icon('heroicon-m-arrow-path')
                        ->action(function () {
                            // Buscar todos os registros que você deseja atualizar
                            $inscritos = Inscrito::all();
                    
                            foreach ($inscritos as $inscrito) {
                                if ($inscrito->pagamento) {
                                    $inscrito->pagamento->atualizarStatus($inscrito);
                                }
                            }
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
