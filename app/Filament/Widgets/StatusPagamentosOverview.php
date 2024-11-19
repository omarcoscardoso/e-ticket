<?php

namespace App\Filament\Widgets;

use App\Models\Pagamento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatusPagamentosOverview extends BaseWidget
{
    
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            // Pagamentos realizados
            Stat::make('Pagamentos', Pagamento::query()->where('status', '=', 'PAID')->count())
                ->description('Pagamentos registrados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            // Pagamentos em análise
            // Stat::make('Em Análise', Pagamento::query()->where('status', '=', 'IN_ANALYSIS')->count())
            //     ->description('Pagamentos aguardando confirmação')
            //     ->color('warning'),

            // Pagamentos recusados ou cancelados
            Stat::make('Cancelados', Pagamento::query()
                ->whereIn('status', ['DECLINED', 'CANCELED'])->count())
                ->description('Pagamentos não aprovados')
                ->color('danger'),

            // Pagamentos aguardando ou isentos
            Stat::make('Aguardando', Pagamento::query()
                ->whereIn('status', ['WAITING', 'IN_ANALYSIS'])->count())
                ->description('Inscrições aguardando pagamento')
                ->Color('warning'),
            
            // Pagamentos aguardando ou isentos
            Stat::make('Isento', Pagamento::query()
                ->whereIn('status', ['FREE'])->count())
                ->description('Inscrições isentas')
                ->Color('info'),
        ];
    }
}
