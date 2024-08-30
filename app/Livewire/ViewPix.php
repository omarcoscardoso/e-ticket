<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

class ViewPix extends Component implements HasForms
{
    public $pixCode = '00020126330014BR.GOV.BCB.PIX0111097449499295204000053039865406170.005802BR5923Presbiterio Catarinense6002SC62210517PGRETIROJOVEM20246304D0D7';

    use InteractsWithForms;

    public ?array $data = [];

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
