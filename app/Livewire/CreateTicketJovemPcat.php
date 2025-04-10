<?php

namespace App\Livewire;

use App\Models\Inscrito;
use App\Models\Ingresso;
use App\Models\Evento;
use App\Models\Pagamento;
use App\Services\PixService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use Leandrocfe\FilamentPtbrFormFields\Document;

class CreateTicketJovemPcat extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public $inscricaoEventoId;
    public $inscricaoCpf;

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Document::make('cpf')
            ->label('CPF')
            // ->default(99982013068)
            ->required()
            ->cpf()
            ->live(true)
            ->afterStateUpdated(function ( Get $get, $set, $state ) {
                $inscricao = Inscrito::query()
                    ->where('evento_id', $get('evento_id'))
                    ->where('cpf', '=', $state)->first();
                if ($inscricao) {
                    $this->inscricaoCpf = $inscricao->cpf;
                    $this->inscricaoEventoId = $inscricao->evento_id;
                    $this->notification();
                }
            }),
            Select::make('evento_id')
                ->label('Evento')
                ->relationship('evento','nome_evento')
                ->default(2)
                ->live()
                ->hidden(true)
                ->required(),
            Select::make('ingresso_id')
                ->label('Ingresso')
                ->placeholder('Escolha um Ingresso')
                ->options(fn(Get $get): Collection => Ingresso::query()
                    ->where('evento_id', $get('evento_id'))
                    ->where('ativo', '=', true)
                    ->pluck('nome', 'id'))
                ->live()
                ->preload()
                ->required()
                ->afterStateUpdated(function (callable $set, $state) {
                    // Busca o ingresso selecionado pelo ID e atualiza o valor do campo 'custo'
                    $ingresso = Ingresso::find($state);
                    $set('custo', $ingresso ? $ingresso->custo : null);
                    if ($ingresso && $ingresso->custo == 0) {
                        $set('tipo_pagamento', 'isento');
                    } else {
                        $set('tipo_pagamento', null);
                    }
                }),
            TextInput::make('custo')
                ->label('Valor')
                ->readOnly()
                ->required(),
            TextInput::make('nome')
                ->required()
                // ->default('Teste'.random_int(1,1000))
                ->maxLength(255),
            DatePicker::make('data_nascimento')
                ->label('Data de Nascimento')
                // ->default(1982)
                ->required(),
            PhoneNumber::make('celular')
                // ->default('519928321'.random_int(10,99))
                ->required()
                ->mask('(99) 99999-9999'),
            Select::make('sexo')
                ->required()
                // ->default('masculino')
                ->options([
                    'masculino' => 'Masculino',
                    'feminino' => 'Feminino'
                ]),
            Select::make('batizado')
                ->required()
                // ->default(true)
                ->boolean(),
            // Select::make('tamanho_camiseta')
            //     ->required()
            //     ->options([
            //         'PP' => 'PP',
            //         'P' => 'P',
            //         'M' => 'M',
            //         'G' => 'G',
            //         'GG' => 'GG',
            //         'XG' => 'XG',
            //     ])
            //     ->placeholder('Selecione o tamanho'),     
            Select::make('igreja_id')
                ->label('Igreja')
                ->relationship(
                    name: 'igreja',
                    titleAttribute: 'nome',
                    modifyQueryUsing: 
                        fn (Builder $query)=> $query
                            ->where('ativo','=',true)
                            ->orderBy('nome')
                            
                )
                // ->default(1)
                ->required(),
            Select::make('tipo_pagamento')
                ->required()
                // ->default('pix')
                ->options([
                    'pix' => 'PIX',
                    'cartao_credito' => 'CARTÃO DE CRÉDITO',
                    // 'local' => 'PAGAR NO DIA DO EVENTO',
                    // 'isento' => 'Isento',
                ]),
        ])
        ->columns([
            'sm' => 1,  // Em dispositivos pequenos (celular), usar 1 coluna
            'lg' => 2,  // Em dispositivos maiores, usar 2 colunas
        ])
        ->statePath('data')
        ->model(Inscrito::class) ;
    }

    public function create()
    {
        $stateData = $this->form->getState();    
        if (!isset($stateData['evento_id'])) {
            $stateData['evento_id'] = 2; // Define o valor padrão ou o desejado
        }

        $dados = Inscrito::query()
                    ->where('evento_id', '=', $stateData['evento_id'])
                    ->where('nome', '=', $stateData['nome'])
                    ->Where('celular', '=', $stateData['celular'])->first();

        switch ($dados) {
            case null:
                $inscrito = Inscrito::create($stateData);
                $atributos = $inscrito->getAttributes();
                $ingresso = Ingresso::find($stateData['ingresso_id']);
                if ($ingresso->custo) {
                    switch ($ingresso->custo) {
                        case 180.0:
                            $link_credito = 'https://pag.ae/7_rQhm14t';
                            break;
                        case 85.0:
                            $link_credito = '';
                            break;
                    }
                }
                switch ($stateData['tipo_pagamento']) {
                    case 'isento':
                        $status = Pagamento::create( [
                            'status' => 'FREE',
                            'inscrito_id' => $atributos['id'],
                            'order_id' => 'FREE_'.random_int(1,999999),
                        ]);
                        session()->flash('status', $status);
                        session()->flash('atributos', $atributos);
                        return redirect()->route('success');
                    case 'local':
                        $status = Pagamento::create( [
                            'status' => 'WAITING',
                            'inscrito_id' => $atributos['id'],
                            'order_id' => 'LOCAL_'.random_int(1,999999),
                        ]);
                        session()->flash('status', $status);
                        session()->flash('atributos', $atributos);
                        return redirect()->route('success');
                    case 'cartao_credito':
                        Pagamento::create( [
                            'status' => 'IN_ANALYSIS',
                            'inscrito_id' => $atributos['id'],
                            'order_id' => 'CREDITO_'.random_int(1,999999),
                        ]);
                        redirect($link_credito);
                        break;
                    case 'pix':
                        $qrcode = PixService::qrcode($atributos, $stateData);
                        $status = Pagamento::create( [
                            'status' => 'WAITING',
                            'inscrito_id' => $atributos['id'],
                            'order_id' => $qrcode['id'],
                        ]);
                        session()->flash('qrcode', $qrcode);
                        session()->flash('status', $status);
                        session()->flash('atributos', $atributos);
                        return redirect()->route('pix');
                    default:
                        redirect('/');
                        break;
                }
                $this->mount();
                break;
            default:
                $this->notification();
                break;
        }
    }

    public function notification()
    {
        Notification::make()
            ->title('CPF já cadastrado')
            ->warning()
            ->icon('heroicon-o-ticket')
            ->color('warning')
            ->persistent()
            ->body('Já exitem Tickets para esse CPF')
            ->actions([
                Action::make('Novo Ingresso')
                     ->button()
                     ->color('success')
                     ->close(),
                Action::make('Ver Tickets')
                    ->button()
                    ->color('info')
                    ->url(route('lsticket', [
                        'inscricaoEventoId' => $this->inscricaoEventoId,
                        'inscricaoCpf' => $this->inscricaoCpf,
                    ])),
            ])
            ->send();
    }

    public function mount(): void
    {
        $this->form->fill();
    }
    public function voltar(): void
    {
        redirect('/');
    }
    public function getCustoAttribute()
    {
        // Supondo que 'ingresso_id' esteja disponível no objeto
        $ingresso = Ingresso::find($this->ingresso_id);
        return $ingresso ? $ingresso->custo : null;
    }
    public function render()
    {
        return view('livewire.create-ticket-jovem-pcat');
    }
}
