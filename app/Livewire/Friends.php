<?php

namespace App\Livewire;

use Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Player;
use App\Models\Game;
use App\Models\Team;

class Friends extends Component
{
    public function render()
    {
        return view('livewire.friends');
    }
}
