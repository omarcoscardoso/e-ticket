<?php

namespace App\Filament\Resources\InscritoResource\Pages;

use App\Filament\Resources\InscritoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditInscrito extends EditRecord
{
    protected static string $resource = InscritoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        
        // atualize o status do pagamento
        $pagamento = $record->pagamento;
        $pagamento->update(['status' => $data['status'],]);
        
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
