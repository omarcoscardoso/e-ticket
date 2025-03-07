<div class="flex flex-col items-center justify-center min-h-screen bg-gray-300 scale-100">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-4xl w-full">
        <h1 class="text-3xl font-bold text-center mb-1">Retiro de Jovens</h1>
        <h2 class="text-2xl font-bold text-center mb-4">Geração que Anuncia Jesus</h2>
        <h3 class="text-2xl font-semibold text-center mb-4">Preencha o Formulário</h3>
        <form wire:submit.prevent="create">
            <div class="space-y-4">
                {{ $this->form }}    
            </div>
            <br>
            <button class="px-10 py-3 bg-green-500 text-white rounded-full hover:bg-green-700" type="submit">
                Enviar
            </button>
            <button class="px-10 py-3 bg-blue-500 text-white rounded-full hover:bg-blue-400"  type="button" onclick="window.location='/'">Voltar</button>
        </form>
        <x-filament-actions::modals />
    </div>
</div>
