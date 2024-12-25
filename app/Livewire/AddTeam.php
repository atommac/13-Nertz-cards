<?php

namespace App\Livewire;

use Livewire\Component;

class AddTeam extends Component
{
    public function increment()
    {
        $this->dispatch('team-added');
    }
}
