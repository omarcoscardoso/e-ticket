<?php
/**
 * 
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\returnArgument;

class InscritosIngressoChart extends ChartWidget
{
    protected static bool $visible = false;
    protected static ?string $heading = 'Inscritos p/ Ticket';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;
    protected function getData(): array
    {
        $data = DB::table('inscritos AS i')
            ->leftJoin('ingressos AS ing', 'i.ingresso_id', '=', 'ing.id')
            ->select(
                DB::raw('COALESCE(ing.nome, "Isento") AS ingressos'),
                DB::raw('COUNT(*) AS quantidade_inscritos')
            )
            ->groupBy('ingressos') // Agrupa apenas por tipo de ingresso
            ->orderBy('quantidade_inscritos', 'desc') // Ordena pela quantidade (opcional)
            ->pluck('quantidade_inscritos', 'ingressos') // Obtém os dados no formato correto
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Quantidade de Inscritos',
                    'data' => array_values($data),
                    'backgroundColor' => ['#28a745', '#ffc107', '#6f42c1', '#fd7e14', '#17a2b8'], // Array de cores (expanda conforme necessário)
                    'borderColor' => '#000000', // Cor da borda
                    'borderWidth' => 1,
                ],
            ],
            'labels' => array_keys($data),
        ];

    }
    protected function getType(): string
    {
        return 'bar';
    }
}
*/