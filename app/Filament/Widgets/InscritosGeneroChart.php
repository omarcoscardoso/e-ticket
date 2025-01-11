<?php

namespace App\Filament\Widgets;

use App\Models\Inscrito;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class InscritosGeneroChart extends ChartWidget
{
    protected static ?string $heading = 'Inscritos p/ Gênero';

    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;
    protected function getData(): array
    {
        // DB::enableQueryLog(); // Habilita o log de queries

        $faixasEtarias = ['Menores de 8 anos', 'De 8 a 12 anos', 'Maiores de 12 anos']; // Define as faixas explicitamente

        $data = DB::table('inscritos')
            ->select(
                'sexo',
                DB::raw("
                    CASE
                        WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) <= 7 THEN 'Menores de 8 anos'
                        WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 8 AND 12 THEN 'De 8 a 12 anos'
                        ELSE 'Maiores de 12 anos'
                    END AS faixa_etaria
                "),
                DB::raw('COUNT(*) as quantidade_inscritos')
            )
            ->groupBy('sexo', 'faixa_etaria')
            ->orderBy('sexo') // Adicionei ordenação para melhor visualização
            ->get()
            ->groupBy('sexo') // Agrupa por sexo para os datasets
            ->map(function ($itens) {
                return $itens->pluck('quantidade_inscritos', 'faixa_etaria');
            })
            ->toArray();
        
        // $queries = DB::getQueryLog();
        // dd($queries); 

        $datasets = [];
        $labels = $faixasEtarias; // Usa as faixas definidas
        $cores = ['#007bff', '#dc3545'];

        foreach (['masculino', 'feminino'] as $index => $sexo) { // Adiciona o índice $index
            $datasetData = [];
            foreach ($faixasEtarias as $faixa) {
                $quantidade = $data[$sexo][$faixa] ?? 0;
                $datasetData[] = $quantidade;
            }
            $datasets[] = [
                'label' => $sexo,
                'data' => $datasetData,
                'backgroundColor' => $cores[$index] ?? '#6c757d', // Cor de fundo da barra
                'borderColor' => $cores[$index] ?? '#6c757d', // Cor da borda da barra
                'borderWidth' => 1, // Largura da borda
            ];
        }
    
        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }
    

    protected function getType(): string
    {
        return 'bar';
    }
}
