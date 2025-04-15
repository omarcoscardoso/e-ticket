<?php

namespace App\Filament\Widgets;

use App\Models\Inscrito;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InscritosOverview extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Inscrições', 
                Inscrito::query()
                    ->where('evento_id', '=', 2)
                    ->count())
                ->description('Inscritos')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                // ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Homens', 
                Inscrito::query()
                    ->where('sexo', '=', 'masculino')
                    ->where('evento_id', '=', 2)
                    ->count())
                ->description('Homens inscritos')
                ->color('info'), 
            Stat::make('Mulheres', 
                Inscrito::query()
                ->where('sexo', '=', 'feminino')
                    ->where('evento_id', '=', 2)
                    ->count())
                ->description('Mulheres inscritas')
                ->color('warning'),
            // Stat::make('Crianças', Inscrito::join('ingressos', 'inscritos.ingresso_id', '=', 'ingressos.id')
            //                                         ->where('ingressos.nome','=','Criança (8 à 12)')->count())
            //     ->description('Crianças de 8 à 12')
            //     ->color('info'),
            // Stat::make('Crianças', Inscrito::join('ingressos', 'inscritos.ingresso_id', '=', 'ingressos.id')
            //                                         ->where('ingressos.custo','=',0)->count())
            //     ->description('Menores de 8 anos')
            //     ->color('info'),
            Stat::make('Não Batizados', Inscrito::query()->where('batizado', '=', false)->count() )
                ->description('Número de inscritos não batizados')
                ->color('default'), 
            
        ];
    }
}
