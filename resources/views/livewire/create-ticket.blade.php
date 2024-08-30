<div class="flex flex-col bg-gray-300 justify-center p-2">
    <form wire:submit="create">
        {{ $this->form }}    
        <button class="px-4 py-2 bg-green-500 hover:bg-green-400 text-white rounded-full my-4" type="submit">
            Enviar
        </button>
    </form>
    <x-filament-actions::modals />
</div>
