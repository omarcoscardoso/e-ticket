<?php

namespace App\Filament\Resources\InscritoResource\Pages;

use App\Filament\Resources\InscritoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInscrito extends CreateRecord
{
    protected static string $resource = InscritoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
