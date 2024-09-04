<?php

namespace App\Livewire;

use App\Models\Inscrito;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;


class CreateTicket extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    
    public function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('nome')
                ->required()
                ->columnSpanFull()
                ->maxLength(255),
            TextInput::make('endereco')
                ->columnSpanFull()
                ->maxLength(255),
            DatePicker::make('data_nascimento')
                ->required()
                ->label('Data de Nascimento'),
            TextInput::make('celular')
                ->required()
                ->tel()
                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
            Radio::make('sexo')
                ->required()
                // ->inline()
                ->options([
                    'masculiNo' => 'Masculino',
                    'feminino' => 'Feminino'
                ]),
            Radio::make('batizado')
                ->required()
                // ->inline()
                ->boolean(),
            Select::make('igreja')
                ->required()
                ->options([
                    'ARARANGUÁ' => 'ARARANGUÁ',
                    'AVENTUREIRO' => 'AVENTUREIRO',
                    '1a BIGUAÇU' => '1a BIGUAÇU',
                    '2a BIGUAÇU' => '2a BIGUAÇU',
                    'BARREIROS' => 'BARREIROS',
                    'BOMBINHAS' => 'BOMBINHAS',
                    'BLUMENAU' => 'BLUMENAU',
                    'CURITIBANOS' => 'CURITIBANOS',
                    'ESTREITO' => 'ESTREITO',
                    '1a JOINVILLE' => '1a JOINVILLE',
                    '2a JOINVILLE' => '2a JOINVILLE',
                    'GOVERNADOR CELSO RAMOS' => 'GOVERNADOR CELSO RAMOS',
                    'IPIRANGA' => 'IPIRANGA',
                    'ITAJAÍ' => 'ITAJAÍ',
                    'ITAPEMA' => 'ITAPEMA',
                    'IRIRIU' => 'IRIRIU',
                    'LAGES' => 'LAGES',
                    'MONTE CARLO' => 'MONTE CARLO',
                    'NAVEGANTES' => 'NAVEGANTES',
                    'PALHOÇA' => 'PALHOÇA',
                    'PALHOÇA AQUARIOS' => 'PALHOÇA AQUARIOS',
                    'PASSO FUNDO' => 'PASSO FUNDO',
                    'PICARRAS' => 'PIÇARRAS',
                    'PORTO ALEGRE' => 'PORTO ALEGRE',
                    'SANTO ÂNGELO' => 'SANTO ÂNGELO',
                    'SÃO FRANCISCO DO SUL' => 'SÃO FRANCISCO DO SUL',
                    'SÃO JOSÉ' => 'SÃO JOSÉ',
                    'VIAMÃO' => 'VIAMÃO',
                    'VIDEIRA' => 'VIDEIRA',
                ])
                ->native(false),
            Textarea::make('observacao')
                ->columnSpanFull(),
            Select::make('tipo_pagamento')
                ->required()
                ->options([
                    'pix' => 'PIX',
                    'cartao_credito' => 'CARTÃO DE CRÉDITO'
                ]),    
        ])->columns(4)
          ->statePath('data');
    }

    public function create(): void
    {
        $stateData = $this->form->getState();
        Inscrito::create($this->form->getState());
        if ($stateData['tipo_pagamento']=='pix') {
            redirect('pix');
        } else {
            redirect('https://mpago.la/1UweKHr');    
        } 
        $this->mount();
    }
    
    public function mount(): void
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.create-ticket');
    }
}
