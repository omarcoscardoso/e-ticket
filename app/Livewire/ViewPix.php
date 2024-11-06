<?php

namespace App\Livewire;

use App\Models\Evento;
use Filament\Notifications\Notification;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Features\SupportTesting\Render;

class ViewPix extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    
    public function mount()
    {
        // Carrega $qrcode da sessão e armazena em $data para uso na view
        $this->data['qrcode'] = session('qrcode');
    }
    public function form(Form $form): Form
    {
        return $form
        ->schema([
            ])->statePath('data');
    }
    
    public function voltar(): void
    {
        redirect('/');
    }
    
    public function render()
    {
        return view('livewire.view-pix');
    }
}
