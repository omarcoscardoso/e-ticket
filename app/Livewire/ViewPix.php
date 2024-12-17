<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

class ViewPix extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    
    public function mount()
    {
        // Carrega $qrcode da sessão e armazena em $data para uso na view
        $this->data['qrcode'] = session('qrcode');
        $this->data['status'] = session('status');
        $this->data['atributos'] = session('atributos');
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

    public function success(): void
    {
        session()->flash('status',  $this->data['status']);
        session()->flash('atributos',  $this->data['atributos']);
        redirect('/success');
    }

    
    public function render()
    {
        return view('livewire.view-pix');
    }
}
