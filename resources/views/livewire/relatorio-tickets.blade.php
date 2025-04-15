<div class="flex-col items-center p-2">
    {{ $this->table }}
    <x-zeus-accordion::accordion activeAccordion="3">

        <x-zeus-accordion::accordion.item
                :isIsolated="true"
                :label="__('Inscritos')"
                icon="bx-user-check"
                badgeColor="danger"
        >
            <div class="bg-white p-4 *:py-2">
                @livewire(\App\Filament\Widgets\InscritosOverview::class)        
            </div>
        </x-zeus-accordion::accordion.item>

        <x-zeus-accordion::accordion.item
                :isIsolated="true"
                :label="__('Inscritos p/ Igreja')"
                icon="bx-building-house"
        >
            <div class="bg-white p-4 *:py-2">
                @livewire(\App\Filament\Widgets\InscritosIgrejaChart::class)        
            </div>
        </x-zeus-accordion::accordion.item>
        
    </x-zeus-accordion::accordion>
   
</div>
