<?php

namespace App\Livewire;

use App\Models\Inscrito;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioTickets extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        // dd(Inscrito::query()->where('evento_id', '=', 2)->get());
        return $table
            ->query(Inscrito::query()
                ->where('evento_id', '=', 2)
                ->with(['pagamento', 'igreja']))
            ->columns([
                TextColumn::make('id_inscrito')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('endereco')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('data_nascimento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('celular')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sexo')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),           
                IconColumn::make('batizado')
                    ->searchable()
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('igreja.nome')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                BadgeColumn::make('pagamento.status')
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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('tipo_pagamento')
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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),         
                TextColumn::make('pagamento.order_id')
                    ->label('order_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),  
                TextColumn::make('observacao')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),    
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\Filter::make('observacao')
                //     ->label("InChurch")
                //     ->query(fn (Builder $query): Builder => $query->where('observacao', '=', "inchurch")),
                Filter::make('inscritos.batizado')
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
                Filter::make('pagamento.status')
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
                //
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('Export')
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
                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.relatorio-tickets');
    }
}
