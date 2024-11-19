<?php

namespace App\Filament\Widgets;

use App\Models\Inscrito;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class InscritosIgrejaChart extends ChartWidget
{
    protected static ?string $heading = 'Inscritos p/ igreja';
    protected static ?int $sort = 2;
    // protected int | string | array $columnSpan = 2;
    protected static string $color = 'success';
    protected function getData(): array
    {

        $data = Inscrito::join('igrejas', 'inscritos.igreja_id', '=', 'igrejas.id')
        ->select('igrejas.nome as igreja', DB::raw('count(inscritos.id) as count'))
        ->groupBy('igrejas.nome')
        ->orderBy('igrejas.nome')
        ->pluck('count', 'igreja')
        ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Inscrito',
                    'data' => array_values($data)
                ]
            ],
            'labels' => array_keys($data),
        ];
        

    }

    protected function getType(): string
    {
        return 'bar';
    }
}
