<?php

namespace App\Filament\Widgets;

use App\Models\Inscrito;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class InscritosIgrejaChart extends ChartWidget
{
    protected static ?string $heading = 'Inscritos p/ igreja';
    protected static ?int $sort = 3;
    protected static string $color = 'success';
    protected function getData(): array
    {
        $data = Inscrito::select('igreja', DB::raw('count(*) as count'))
        ->groupBy('igreja')
        ->pluck('count', 'igreja')
        ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Inscritos',
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
