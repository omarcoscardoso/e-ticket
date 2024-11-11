<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;
    
    protected $table = 'pagamentos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'status',
        'inscrito_id',
        'order_id',
    ];
    static function status_pagamento($orderId)
    {
        // $endpoint = 'https://sandbox.api.pagseguro.com/orders/'.$orderId;
        $endpoint = 'https://api.pagseguro.com/orders/'.$orderId;
        // $token = '6b78552e-a570-4009-aef1-08a359724241df7d76194619b9d708594cf45217b0e39615-69c3-4fa7-b2ce-0c5142182d9d'; // sandbox
        $token = 'd943f521-b1d8-4459-8d95-235fa2f7e6ca5707c8514502abba78ba93015bafa78e2ed5-5db4-4fb1-808a-3e61e1355bfe'; // production

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

          $data = $data['charges'][0]['status'] ?? null;

          return $data;

    }
    static function atualizarStatus($inscrito)
    {
        $data = self::status_pagamento($inscrito->pagamento?->order_id);
        if ($data !== null) {
          $inscrito->pagamento?->update(['status' => $data]);
        }
    }
    public function inscrito()
    {
        return $this->belongsTo(Inscrito::class);
    }
}
