<?php

namespace App\Livewire;

use App\Livewire\ViewPix;
use App\Models\Inscrito;
use App\Models\Ingresso;
use App\Models\Evento;
use App\Models\Pagamento;
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
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Livewire\Livewire;

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
                ->default(1)
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
                        // Define tipo_pagamento como isento
                        $set('tipo_pagamento', 'isento');
                    } else {
                        $set('tipo_pagamento', 'pix');
                    }
                }),
            TextInput::make('custo')
                ->label('Valor')
                ->readOnly()
                ->required(),
            TextInput::make('nome')
                ->required()
                ->default('Teste'.random_int(1,1000))
                ->maxLength(255),
            DatePicker::make('data_nascimento')
                ->label('Data de Nascimento')
                ->default(1982)
                ->required(),
            Document::make('cpf')
                ->label('CPF')
                ->default(99982013068)
                ->required()
                ->cpf(),
            PhoneNumber::make('celular')
                ->default('519928321'.random_int(10,99))
                ->mask('(99) 99999-9999'),
            Select::make('sexo')
                ->required()
                ->default('masculino')
                ->options([
                    'masculino' => 'Masculino',
                    'feminino' => 'Feminino'
                ]),
            Select::make('batizado')
                ->required()
                ->default(true)
                ->boolean(),
            Select::make('tamanho_camiseta')
                ->required()
                ->default('M')
                ->options([
                    'PP' => 'PP',
                    'P' => 'P',
                    'M' => 'M',
                    'G' => 'G',
                    'GG' => 'GG',
                    'XG' => 'XG',
                ])
                ->placeholder('Selecione o tamanho'),     
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
                ->required(),
            Select::make('tipo_pagamento')
                ->required()
                ->default('pix')
                ->options([
                    'pix' => 'PIX',
                    // 'cartao_credito' => 'CARTÃO DE CRÉDITO',
                    'isento' => 'Isento',
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
            $stateData['evento_id'] = 1; // Define o valor padrão ou o desejado
        }

        $dados = Inscrito::query()
                    ->where('nome', '=', $stateData['nome'])
                    ->Where('celular', '=', $stateData['celular'])->first();
        switch ($dados) {
            case null:
                $inscrito = Inscrito::create($stateData);
                $atributos = $inscrito->getAttributes();
                $ingresso = Ingresso::find($stateData['ingresso_id']);
                if ($ingresso->custo) {
                    switch ($ingresso->custo) {
                        case 170.0:
                            $link = 'https://pag.ae/7_2sAxW4o';
                            break;
                        case 85.0:
                            $link = 'https://pag.ae/7_482MVCL';
                            break;
                    }
                }
                switch ($stateData['tipo_pagamento']) {
                    case 'isento':
                        Pagamento::create( [
                            'status' => 'FREE',
                            'inscrito_id' => $atributos['id'],
                            'order_id' => 'FREE'.random_int(1,99),
                        ]);
                        redirect('/');
                        break;
                    case 'pix':
                        $qrcode = $this->qrcode($atributos,dadosform: $stateData);
                        $status = Pagamento::create( [
                            'status' => 'WAITING',
                            'inscrito_id' => $atributos['id'],
                            'order_id' => $qrcode['id'],
                        ]);
                        session()->flash('qrcode', $qrcode);
                        return redirect()->route('pix');
                    default:
                        redirect($link);
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
    
    static function status_pagamento($order_id)
    {
        $endpoint = 'https://sandbox.api.pagseguro.com/orders/'.$order_id;
        $token = '6b78552e-a570-4009-aef1-08a359724241df7d76194619b9d708594cf45217b0e39615-69c3-4fa7-b2ce-0c5142182d9d';

          $curl = curl_init();
          curl_setopt($curl, CURLOPT_URL, $endpoint);
          curl_setopt($curl, CURLOPT_POST, false);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
          curl_setopt($curl, CURLOPT_CAINFO, "cacert.pem");
          curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type:application/json',
            'Authorization: Bearer ' . $token
          ]);
      
          $response = curl_exec($curl);
          $error = curl_error($curl);

          curl_close($curl);
      
          if ($error) {
            var_dump($error);
            die();
          }
      
          $data = json_decode($response, true);

          $data = $data['charges'][0]['status'];

          return $data;

    }

    static function qrcode($atributos, $dadosform)
    {
        // $endpoint = 'https://sandbox.api.pagseguro.com/orders';
        // $token = '6b78552e-a570-4009-aef1-08a359724241df7d76194619b9d708594cf45217b0e39615-69c3-4fa7-b2ce-0c5142182d9d'; // sandbox
        $endpoint = 'https://api.pagseguro.com/orders';
        $token = 'd943f521-b1d8-4459-8d95-235fa2f7e6ca5707c8514502abba78ba93015bafa78e2ed5-5db4-4fb1-808a-3e61e1355bfe'; // production

    
        $evento = Evento::query()->where('id', '=', $atributos['evento_id'])->value('nome_evento');

        $pattern = '/\((\d{2})\)\s*([\d-]+)/';
        if (preg_match($pattern, $atributos['celular'], $matches)) {
            $ddd = $matches[1];
            $numero = str_replace('-', '', $matches[2]);
        }
        $body =
          [
            "reference_id" => "ticket-".$atributos['evento_id']."x".$atributos['ingresso_id']."x".$atributos['id'],
            "customer" => [
              "name" => $atributos['nome'],
              "email" => "ticket@iprviamao.com.br",
              "tax_id" => preg_replace('/\D/', '', $atributos['cpf']),
              "phones" => [
                [
                  "country" => "55",
                  "area" => $ddd,
                  "number" => $numero,
                  "type" => "MOBILE"
                ]
              ]
            ],
            "items" => [
              [
                "name" => $evento,
                "quantity" => 1,
                "unit_amount" => $dadosform['custo']."00"
              ]
            ],
            "qr_codes" => [
              [
                "amount" => [
                  "value" => $dadosform['custo']."00"
                ],
                "expiration_date" => "2024-12-29T20:15:59-03:00",
              ]
            ],
            "shipping" => [
              "address" => [
                "street" => "Jose Garibalde",
                "number" => "1455",
                "complement" => "n/a",
                "locality" => "Estalagem",
                "city" => "Viamão",
                "region_code" => "RS",
                "country" => "BRA",
                "postal_code" => "94425052"
              ]
            ],
            "notification_urls" => [
              "https://renovada.app.br/notifications"
            ]
          ];
    
        // dd($body);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_CAINFO, "cacert.pem");
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
          'Content-Type:application/json',
          'Authorization: Bearer ' . $token
        ]);
    
        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);
    
        if ($error) {
          var_dump($error);
          die();
        }
        
        dd($response);

        $data = json_decode($response, true);
    
        return $data;
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
        return view('livewire.create-ticket');
    }
}
