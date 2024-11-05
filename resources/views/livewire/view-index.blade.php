<div class="flex flex-col items-center justify-center min-h-screen bg-gray-300">
    <div class="bg-white p-3 rounded-2xl shadow-md max-w-2xl">
        <video class="w-full aspect-video rounded-2xl h-auto mb-7" controls>
            <source src="retiro_verao_2025.mp4" type="video/mp4">
            Seu navegador não suporta a reprodução de vídeo.
        </video>
        <h1 class="text-2xl font-bold text-center">Retiro de Verão</h1>
        <h1 class="text-2xl font-bold text-center">De 24 a 26 de Janeiro</h1>
        <p class="text-center">Prepare-se para um fim de semana de transformação e crescimento espiritual.</p>
        <br>
        <div class="text-center"> 
            <button type="button" class="bg-blue-500 text-white px-3 py-1 rounded-md">
                Doutor Artur José Soares, 3920<br>
                Morungava - Gravataí/RS<br>
                Sítio Ebenézer Sul
            </button>
        <script>
            const button = document.querySelector('button');
            button.addEventListener('click', () => {
                window.open('https://maps.app.goo.gl/TA3Y8TVNV6r1gne7A', '_blank');
            });
        </script>
        </div>
        <br>
        <p class="text-center">Junte-se a nós para momentos de louvor, estudo da Palavra, e conexão com Deus e com o próximo!</p>
        <br>
        <form wire:submit="submit">
            {{ $this->form }}
            <div class="text-center">
                <button class="px-10 py-6 bg-green-500 text-white rounded-full hover:bg-green-700"  type="submit">Inscreva-se Agora</button>
            </div>
        </form>
        <div class="mt-4 text-sm text-gray-500 text-center">
            ©2024 Igreja Presbiteriana Renovada de Viamão<br>
            Presbitério Catarinense
        </div>
    </div>
</div>