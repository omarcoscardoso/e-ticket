<?php

namespace App\Filament\Widgets;

use App\Models\Evento;
use App\Models\Inscrito;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InscritosOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Inscrito', Inscrito::query()->count())
                ->description('Jovens inscritos')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
             Stat::make('Não Batizados', Inscrito::query()->where('batizado', '=', false)->count() )
                ->description('Número de inscritos não batizados')
                ->color('danger'),
            Stat::make('Homens', Inscrito::query()->where('sexo', '=', 'masculino')->count())
                ->description('Homens inscritos')
                ->color('info'), 
            Stat::make('Mulheres', Inscrito::query()->where('sexo', '=', 'feminino')->count())
                ->description('Mulheres inscritas')
                ->color('warning'), 
            
        ];
    }
}
