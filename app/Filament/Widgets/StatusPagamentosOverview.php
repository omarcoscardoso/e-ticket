<?php

namespace App\Filament\Widgets;

use App\Models\Inscrito;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatusPagamentosOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pagamentos', Inscrito::query()->where('situacao_pagamento', '=', 'pago')->count())
            ->description('Pagamentos registrados')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('warning'),
        Stat::make('Em Aberto', Inscrito::query()->where('situacao_pagamento', '=', 'aberto')
                                                 ->orWhere('situacao_pagamento', '=', null) ->count())
            ->description('Inscrições sem Pagamento identificado')
            ->color('danger'),
        ];
    }
}
