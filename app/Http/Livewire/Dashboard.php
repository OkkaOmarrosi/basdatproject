<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Dashboard extends Component
{

    public function test()
    {
        // Show toast message
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'AWKK' , 'message' => 'Success message']);
    }

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
