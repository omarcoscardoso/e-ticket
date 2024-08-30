<?php

namespace App\Filament\Resources\InscritoResource\Pages;

use App\Filament\Resources\InscritoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInscrito extends EditRecord
{
    protected static string $resource = InscritoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
