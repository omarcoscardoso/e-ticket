<?php

namespace App\Livewire;

use App\Models\Inscrito;
use App\Models\Ingresso;
use App\Models\Evento;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Forms\Get;
use GrahamCampbell\ResultType\Success;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class CreateTicket extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    
    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Select::make('evento_id')
                ->label('Evento')
                ->relationship('evento','nome_evento')
                ->preload()
                ->live()
                ->default(1)
                ->disabled()
                ->required(),
            Select::make('ingresso_id')
                ->label('Escolha um Ingresso')
                ->options(fn(Get $get): Collection => Ingresso::query()
                    ->where('evento_id', $get('evento_id'))
                    ->where('ativo', '=', true)
                    ->pluck('nome', 'id'))
                ->preload()
                ->required(),
            TextInput::make('nome')
                ->required()
                ->columnSpan(3)
                ->maxLength(255),
            DatePicker::make('data_nascimento')
                ->label('Data de Nascimento')
                ->required(),
            PhoneNumber::make('celular')
                ->mask('(99) 99999-9999'),
            Select::make('sexo')
                ->required()
                ->options([
                    'masculino' => 'Masculino',
                    'feminino' => 'Feminino'
                ]),
            Select::make('batizado')
                ->required()
                ->boolean(),      
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
                ->default(1)
                ->disabled()
                ->required(),
            Select::make('tipo_pagamento')
                ->required()
                ->options([
                    'pix' => 'PIX',
                    'cartao_credito' => 'CARTÃO DE CRÉDITO'
                ]),
        ])
        ->columns(4)
        ->statePath('data')
        ->model(Inscrito::class) ;
    }

    public function create(): void
    {
        $stateData = $this->form->getState();
        $dados = Inscrito::query()
                    ->where('nome', '=', $stateData['nome'])
                    ->Where('celular', '=', $stateData['celular'])->first();
        switch ($dados) {
            case null:
                Inscrito::create($this->form->getState());
                switch ($stateData['tipo_pagamento']) {
                    case 'pix':
                        redirect('pix');
                        break;
                    default:
                        redirect('https://mpago.la/1UweKHr');
                        break;
                }
                $this->mount();
                break;
            default:
                Notification::make()
                    ->title('NOME ou CELULAR já cadastrados')
                    ->warning()
                    ->color('warning')
                    ->persistent()
                    ->body('Se você ainda não fez o PAGAMENTO escolha uma opção abaixo.')
                    ->actions([
                        Action::make('PIX')
                            ->button()
                            ->color('success')
                            ->url('pix'),
                        Action::make('CREDITO')
                            ->button()
                            ->color('info')
                            ->url('https://mpago.la/1UweKHr'),
                    ])
                    ->send();
                break;
        }
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
