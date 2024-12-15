<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Livewire\Component;

class Success extends Component
{

    public ?array $data = [];

    public function mount()
    {
        // Carrega dados da sessão e armazena em $data para uso na view
        $this->data['local'] = session('local');
        $this->data['atributos'] = session('atributos');
    }
    public function voltar(): void
    {
        redirect('/');
    }
    public function render()
    {
        return view('livewire.success');
    }
}
