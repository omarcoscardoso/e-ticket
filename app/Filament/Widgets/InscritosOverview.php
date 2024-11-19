<?php

namespace App\Filament\Widgets;

use App\Models\Inscrito;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InscritosOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 2;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Inscrito', Inscrito::query()->count())
                ->description('Inscritos')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Homens', Inscrito::query()->where('sexo', '=', 'masculino')->count())
                ->description('Homens inscritos')
                ->color('info'), 
            Stat::make('Mulheres', Inscrito::query()->where('sexo', '=', 'feminino')->count())
                ->description('Mulheres inscritas')
                ->color('warning'),
            Stat::make('Crianças', Inscrito::query()->where('ingresso_id', '=', '2')->count())
                ->description('(8 à 12)')
                ->color('info'),
            Stat::make('Crianças', Inscrito::query()->where('ingresso_id', '=', '3')->count())
                ->description('ISENTO')
                ->color('info'),
            Stat::make('Não Batizados', Inscrito::query()->where('batizado', '=', false)->count() )
                ->description('Número de inscritos não batizados')
                ->color('default'), 
            
        ];
    }
}
