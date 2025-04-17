<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class InscritosIdadeChart extends ChartWidget
{
    protected static ?string $heading = 'Inscritos p/ Idade';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data = DB::table('inscritos')
        ->selectRaw(
            'CASE
                WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) > 25 THEN "Adulto (30+)"
                WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 19 AND 25 THEN "Jovem (20-30)"
                WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 16 AND 18 THEN "Jovem (16-19)"
                WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 11 AND 15 THEN "Adolescente (11-15)"
                WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 1 AND 10 THEN "Criança"
                ELSE "Não informada"
            END AS faixa_etaria, COUNT(*) AS quantidade'
        )
        ->groupBy('faixa_etaria')
        ->orderBy('faixa_etaria') // Opcional: ordenar as faixas etárias
        ->pluck('quantidade', 'faixa_etaria')
        ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Quantidade de Inscritos por Faixa Etária',
                    'data' => array_values($data),
                    'backgroundColor' => ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d'], // Cores para cada faixa etária
                    'borderColor' => '#000000',
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
