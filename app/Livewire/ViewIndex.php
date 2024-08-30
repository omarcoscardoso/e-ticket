<?php

namespace App\Livewire;

use App\Models\Inscrito;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class ViewIndex extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
        ->schema([  
        ])->statePath('data');
    }
    
    public function submit(): void
    {
        redirect('ticket');
    }
    
    public function mount(): void
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.view-index');
    }
}
