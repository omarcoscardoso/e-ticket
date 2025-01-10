<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-teal-500 via-cyan-500 to-teal-400">
    <div class="relative max-w-xl w-11/12 mx-auto p-6 bg-white rounded-md shadow-lg text-center text-teal-600">
        <h1 class="font-sans text-3xl md:text-4xl font-bold mb-6">
            Inscrição concluída!
        </h1>
        {{ $tamanho_camiseta = isset($data['atributos']['tamanho_camiseta']) ? $data['atributos']['tamanho_camiseta'] : 'Não informado'  }}

        <!-- Div ajustada para centralizar o ícone SVG -->
        <div class="flex items-center justify-center w-full">
            <div class="w-20 h-20">
                <svg version="1.1" id="tick" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="10px" y="0px"
                    viewBox="0 0 37 37" style="enable-background:new 0 0 37 37;" xml:space="preserve">
                    <path class="circ path" style="fill:#0cdcc7;stroke:#07a796;stroke-width:3;stroke-linejoin:round;stroke-miterlimit:10;" d="M30.5,6.5L30.5,6.5c6.6,6.6,6.6,17.4,0,24l0,0c-6.6,6.6-17.4,6.6-24,0l0,0c-6.6-6.6-6.6-17.4,0-24l0,0C13.1-0.2,23.9-0.2,30.5,6.5z"/>
                    <polyline class="tick path" style="fill:none;stroke:#fff;stroke-width:3;stroke-linejoin:round;stroke-miterlimit:10;" points="11.6,20 15.9,24.2 26.4,13.8 "/>
                </svg>
            </div>
        </div>
        <div class="mt-6">
            <p class="font-sans text-base md:text-lg text-gray-700 mb-4">
                Você se inscreveu para o Retiro de Verão 2025! <br><br>
                <span class="font-semibold">Data:</span> {{$data['status']->getAttributes()['updated_at']}}<br>
                <span class="font-semibold">Identificador:</span> {{$data['status']->getAttributes()['order_id']}}<br>
                <span class="font-semibold">Nome:</span> {{$data['atributos']['nome']}}<br>
                <span class="font-semibold">CPF:</span> {{$data['atributos']['cpf']}} <br>
                <span class="font-semibold">Celular:</span> {{$data['atributos']['celular']}} <br>
                <span class="font-semibold">Camiseta:</span> {{ $tamanho_camiseta }} <br>
                <span class="font-semibold">Tipo Pagamento:</span> {{$data['atributos']['tipo_pagamento']}} <br>
            </p>
            
            <!-- Botão para gerar o PDF e compartilhar -->
            <a 
               href="https://wa.me/?text={{ rawurlencode(
                    "Você se inscreveu para o Retiro de Verão 2025!\n\n" .
                    "Data: " . $data['status']->getAttributes()['updated_at'] . "\n" .
                    "Identificador: " . $data['status']->getAttributes()['order_id'] . "\n" .
                    "Nome: " . $data['atributos']['nome'] . "\n" .
                    "CPF: " . $data['atributos']['cpf'] . "\n" .
                    "Celular: " . $data['atributos']['celular'] . "\n" .
                    "Camiseta: " . $tamanho_camiseta . "\n" .
                    "Tipo Pagamento: " . $data['atributos']['tipo_pagamento']
                ) }}" 
               target="_blank" 
               class="px-4 py-2 bg-green-500 text-white rounded-lg">
               Enviar para WhatsApp
            </a>
            <br><br><br>
            <a 
               href="/ticket" 
               target="_self" 
               class="px-4 py-2 bg-blue-500 text-white rounded-lg">
               Nova inscrição
            </a>
        </div>
        <p class="mt-4 text-sm text-teal-700 font-medium">@iprviamao</p>
    </div>
</div>

<script>
    window.addEventListener("load", function () {
        setTimeout(function () {
            document.querySelector('.circ').classList.add("stroke-dashoffset-0");
            document.querySelector('.tick').classList.add("stroke-dashoffset-0");
        }, 500);
    });
</script>

