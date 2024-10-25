<?php

namespace App\Filament\Resources\IngressoResource\Pages;

use App\Filament\Resources\IngressoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageIngressos extends ManageRecords
{
    protected static string $resource = IngressoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
