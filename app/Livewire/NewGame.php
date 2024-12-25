<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class NewGame extends Component
{
    public $teams[];

    public function render()
    {
        return view('livewire.new-game');
    }
}
