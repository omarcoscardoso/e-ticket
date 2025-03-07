
<div class="bg-gray-300 p-8">
    <div class="col-2 bg-gray-100 rounded-lg shadow-md overflow-hidden flex flex-col">
        <div class="flex flex-col items-center">
            <!-- <h2 class="text-2xl font-bold text-gray-800">Retiro de Verão 2025</h2> -->
            <h1 class="text-2xl font-bold text-center">Retiro de Jovens - PCAT</h1>
            <h1 class="text-1xl font-bold text-center">Geração que Anuncia Jesus</h1>
            <h1 class="text-2xl font-bold text-gray-800 mb-5 mt-5">Dados PIX de Pagamento</h1>
            <p class="font-bold text-3xl text-gray-800">Valor: R$ {{ number_format($data['qrcode']['qr_codes'][0]['amount']['value'] / 100, 2, ',', '.') }}</p>
            <div class="flex-col-reverse flex lg:flex-col my-2">
            <img src="{{ $data['qrcode']['qr_codes'][0]['links'][0]['href'] }}" alt="">
                <form wire:submit="success">
                    {{ $this->form }}
                    <textarea id="dado" class="border-2 border-gray-300 rounded-md w-full sm:w-84 break-words mb-2 text-sm font-semibold p-1" readonly>{{ $data['qrcode']['qr_codes'][0]['text'] }}</textarea>
                    <div class="flex flex-col">
                        <button onclick="copyToClickBoard()" class="px-4 py-2 bg-green-500 hover:bg-green-400 text-white text-2xl rounded-full my-1">
                            PIX Copia e Cola
                        </button>
                        <!-- <button class="px-4 py-2 bg-blue-500 hover:bg-blue-400 text-white text-2xl rounded-full my-4"  type="submit">Voltar</button> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
<script>
    function copyToClickBoard() {
        var content = document.getElementById('dado').innerHTML;
        navigator.clipboard.writeText(content).then(function() {
            alert('Código PIX copiado! Abra o aplicativo do seu banco para colar e concluir o pagamento.');
        }).catch(function(error) {
            console.error('Erro ao copiar para a área de transferência:', error);
        });
    }
</script>

