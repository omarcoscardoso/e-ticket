<?php

namespace App\Services;

use App\Models\Evento;
use App\Models\Pagamento;

class PixService
{
    static function qrcode($atributos, $dadosform)
    {    
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
                "expiration_date" => "2025-04-21T23:59:00-03:00",
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
        $endpoint = env('PAGBANK_ENDPOINT');
        $token = env('PAGBANK_TOKEN'); 

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

        $data = json_decode($response, true);
        
        // dd($data);
        
        return $data;
    }
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
}
