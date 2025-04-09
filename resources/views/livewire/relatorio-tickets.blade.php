<div class="flex-col items-center p-2">
    <x-zeus-accordion::accordion activeAccordion="3">

        <x-zeus-accordion::accordion.item
                :isIsolated="true"
                :label="__('Inscritos')"
                icon="heroicon-o-chevron-right"
                badgeColor="danger"
        >
            <div class="bg-white p-4 *:py-2">
                @livewire(\App\Filament\Widgets\InscritosOverview::class)        
            </div>
        </x-zeus-accordion::accordion.item>
    
        <x-zeus-accordion::accordion.item
                :isIsolated="true"
                :label="__('Pagamentos')"
                icon="heroicon-o-chevron-right"
        >
            <div class="bg-white p-4 *:py-2">
                @livewire(\App\Filament\Widgets\StatusPagamentosOverview::class)        
            </div>
        </x-zeus-accordion::accordion.item>

        <x-zeus-accordion::accordion.item
                :isIsolated="true"
                :label="__('Igrejas')"
                icon="heroicon-o-chevron-right"
        >
            <div class="bg-white p-4 *:py-2">
                @livewire(\App\Filament\Widgets\InscritosIgrejaChart::class)        
            </div>
        </x-zeus-accordion::accordion.item>
    
    </x-zeus-accordion::accordion>

    {{ $this->table }}
</div>
