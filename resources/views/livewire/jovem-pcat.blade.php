<div class="flex flex-col items-center justify-center min-h-screen bg-gray-300">
    <div class="bg-white p-3 rounded-2xl shadow-md max-w-2xl">
        <img class="w-full aspect-video rounded-2xl h-auto mb-7" src="capa_jovem_pcat.png"/>
        <h1 class="text-2xl font-bold text-center">Retiro de Jovens - PCAT</h1>
        <h1 class="text-2xl font-bold text-center">Geração que Anuncia Jesus</h1>
        <h1 class="text-2xl font-bold text-center">De 18 a 20 de Abril</h1>
        <p class="text-center">Prepare-se para um fim de semana de transformação e crescimento espiritual.</p>
        <br>
        <div class="text-center"> 
            <button type="button" class="bg-blue-500 text-white px-3 py-1 rounded-md">
                Rua Waldemiro José Borges 4911<br>
                Joinville/SC<br>
                Recanto da paz
            </button>
        <script>
            const button = document.querySelector('button');
            button.addEventListener('click', () => {
                window.open('https://maps.app.goo.gl/hRKJV2KqcpLvkwuQA', '_blank');
            });
        </script>
        </div>
        <br>
        <p class="text-center">Junte-se a nós para momentos de louvor, Palavra, e conexão com Deus e com o próximo!</p>
        <br>
        <form wire:submit="submit">
           
            <div class="text-center">
                <button class="px-10 py-6 bg-green-500 text-white rounded-full hover:bg-green-700"  type="submit">Inscreva-se Agora</button>
            </div>
        </form>
        <div class="mt-4 text-sm text-gray-500 text-center">
            ©2025 Igreja Presbiteriana Renovada<br>
            Presbitério Catarinense
        </div>
    </div>
</div>
