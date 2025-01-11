<?php

namespace App\Filament\Widgets;

use App\Models\Inscrito;
use App\Models\Pagamento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatusPagamentosOverview extends BaseWidget
{
    
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        $sinal = '1000';
        
        $data_pagar = Inscrito::join('ingressos', 'inscritos.ingresso_id', '=', 'ingressos.id')
        ->select('ingressos.nome', DB::raw('count(inscritos.id) as count'))
        ->groupBy('ingressos.nome')
        ->pluck('count', 'nome');
        
        $pagar_adulto = $data_pagar->get('Adulto')*110;
        $pagar_criança = $data_pagar->get('Criança (8 à 12)')*55;
        // $pagar_isento = $data_pagar->get('Criança até 7 anos');

        return [
            Stat::make('Total a pagar (R$)', number_format($pagar_adulto+$pagar_criança-$sinal,2,",","."))
                ->description('Total de inscritos - Entrada')
                ->descriptionIcon('heroicon-c-currency-dollar')
                ->chart([7, 2, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100])
                ->color('danger'),

            Stat::make('Ing. Adulto (R$)', number_format($pagar_adulto,2,",","."))
                ->description(' ')
                ->descriptionIcon('heroicon-c-user')
                ->color('danger'),

            Stat::make('Ing. Criança (R$)', number_format($pagar_criança,2,",","."))
                ->description(' ')
                ->descriptionIcon('heroicon-c-users')
                ->color('danger'),

            Stat::make('Entrada (R$)', number_format($sinal,2,",","."))
                ->description('Sinal para Evento')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),                
            // Pagamentos realizados
            Stat::make('Pagamentos', Pagamento::query()->where('status', '=', 'PAID')->count())
                ->description('Pagamentos registrados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            // Pagamentos em análise
            Stat::make('Em Análise', Pagamento::query()->where('status', '=', 'IN_ANALYSIS')->count())
                ->description('Pagamentos aguardando confirmação')
                ->color('warning'),

            // Pagamentos recusados ou cancelados
            // Stat::make('Cancelados', Pagamento::query()
            //     ->whereIn('status', ['DECLINED', 'CANCELED'])->count())
            //     ->description('Pagamentos não aprovados')
            //     ->color('danger'),

            // Pagamentos aguardando ou isentos
            Stat::make('Aguardando', Pagamento::query()
                ->whereIn('status', ['WAITING', 'IN_ANALYSIS'])->count())
                ->description('Inscrições aguardando pagamento')
                ->Color('warning'),
            
            // Pagamentos aguardando ou isentos
            // Stat::make('Isento', Pagamento::query()
            //     ->whereIn('status', ['FREE'])->count())
            //     ->description('Inscrições isentas')
            //     ->Color('info'),
                
        ];
    }
}
