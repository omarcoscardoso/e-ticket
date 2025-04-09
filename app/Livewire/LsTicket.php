<?php

namespace App\Livewire;

use App\Models\Ingresso;
use App\Models\Inscrito;
use App\Models\Pagamento;
use App\Services\PixService;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Split;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LsTicket extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public ?array $data = [];
    public $inscricaoCpf;
    public $inscricaoEventoId;
    
    public function mount($inscricaoEventoId, $inscricaoCpf)
    {
        // $this->data['inscricaoCpf'] = $this->params['inscricaoCpf'] ?? null;
        // $this->data['inscricaoEventoId'] = $this->params['inscricaoEventoId'] ?? null;
        $this->inscricaoCpf = $inscricaoCpf;
        $this->inscricaoEventoId = $inscricaoEventoId;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(Inscrito::query()
                ->where('cpf', '=',$this->inscricaoCpf)
                ->where('evento_id', '=', $this->inscricaoEventoId)
            )
            ->columns([
                Split::make([
                    TextColumn::make('evento.nome_evento')
                        ->icon('bx-calendar-event')
                        ->sortable(),
                    TextColumn::make('nome')
                        ->icon('bx-user-pin')
                        ->sortable(),
                    TextColumn::make('pagamento.status')
                        ->label('Status')
                        ->icon('bx-money')
                        ->badge()
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
                        ->sortable(),
                ])->from('md')
            ])
            ->actions([
                Action::make('gerarPix')
                    ->label('PIX')
                    ->icon('bx-qr')
                    ->button()
                    ->color('success')
                    ->action(function (Inscrito $record, PixService $qrcodeService) {
                        $atributos = $record;
                        $stateData = Ingresso::query()
                            ->where('id', '=',$atributos->ingresso_id)
                            ->first();                        
                        // Classe de serviço para gerar o QR Code
                        $qrcode = $qrcodeService->qrcode($atributos, $stateData);

                        Pagamento::where('inscrito_id', $atributos->id)
                            ->update([
                                'status' => 'WAITING',
                                'order_id' => $qrcode['id'],
                        ]);
                        $status = Pagamento::where('inscrito_id', $atributos->id)->first();
                     
                        Session::flash('qrcode', $qrcode);
                        Session::flash('status', $status);
                        Session::flash('atributos', $atributos);
                        return Redirect::route('pix');

                    })->disabled(fn (Inscrito $record): bool => $record->pagamento === null || $record->pagamento->status === 'PAID'),
                Action::make('pagarCredito')
                    ->label('Cartão de Crédito')
                    ->icon('bx-credit-card')
                    ->button()
                    ->color('primary')
                    ->url(function (Inscrito $record): ?string {
                        $ingresso = Ingresso::find($record->ingresso_id);
                        Pagamento::where('inscrito_id', $record->id)
                            ->update([
                                'status' => 'IN_ANALYSIS',
                                'inscrito_id' => $record['id'],
                                'order_id' => 'CREDITO_'.random_int(1,999999),
                        ]);
                        if ($ingresso && $ingresso->custo) {
                            switch ($ingresso->custo) {
                                case 180.0:
                                    return 'https://pag.ae/7_rQhm14t';
                                default:
                                    return null; // Desabilita o botão para outros custos
                            }
                        }
                        return null; // Desabilita o botão se não houver ingresso ou custo
                    })
                    ->openUrlInNewTab(true) // Opcional: abrir o link em uma nova aba
                    ->visible(function (Inscrito $record): bool {
                        $ingresso = Ingresso::find($record->ingresso_id);
                        return $ingresso && $ingresso->custo === 180.0; // Tornar visível apenas para o custo de 180.0
                    })->disabled(fn (Inscrito $record): bool => $record->pagamento === null || $record->pagamento->status === 'PAID'),
            ]);
    }
    public function render()
    {
        return view('livewire.ls-ticket');
    }
}
