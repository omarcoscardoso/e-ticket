<div class="flex flex-col items-center justify-center min-h-screen bg-gray-400">
    <div class="bg-white p-3 rounded-2xl shadow-md max-w-2xl">
        <video class="w-full aspect-video rounded-2xl h-auto mb-7" controls>
            <source src="https://renovada.app.br/filme_jovens1.mp4" type="video/mp4">
            Seu navegador não suporta a reprodução de vídeo.
        </video>
        <h1 class="text-2xl font-bold text-center">Encontro de Jovens da Igreja Presbiteriana Renovada</h1>
        <h1 class="text-2xl font-bold text-center">De 13 a 15 de setembro</h1>
        <p class="text-center">Prepare-se para um fim de semana de transformação e crescimento espiritual.</p>
        <br>    
        <div class="text-center"> 
            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                Estrada do Saltinho, 354-526<br>
                CEPROVI, Campo Alegre/SC
            </button>
        <script>
            const button = document.querySelector('button');
            button.addEventListener('click', () => {
                window.open('https://maps.app.goo.gl/FBdjKUbMb2KFcMCm6', '_blank');
            });
        </script>
        </div>
        <br>
        <p class="text-center">Junte-se a nós no Espaço Ceprovi para momentos de louvor, estudo da Palavra, e conexão com outros jovens!</p>
        <br>
        <form wire:submit="submit">
            {{ $this->form }}
            <div class="text-center">
                <button class="px-10 py-6 bg-green-500 text-white rounded-full hover:bg-green-700"  type="submit">Inscreva-se Agora</button>
            </div>
        </form>
        <div class="mt-4 text-sm text-gray-500 text-center">
            ©2024 Igreja Presbiteriana Renovada<br>
            Presbitério Catarinense
        </div>
    </div>
</div>