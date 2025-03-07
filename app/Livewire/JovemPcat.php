<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Livewire\Form;

class JovemPcat extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    
    public function submit(): void
    {
        redirect('ticketjovem');
    }
    
    public function mount(): void
    {
        $this->form->fill();
    }
    public function render()
    {
        return view('livewire.jovem-pcat');
    }
}
