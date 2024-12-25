<?php

namespace App\Livewire;

use Livewire\Component;

class Card extends Component
{
    public $suit = ["spades", "hearts", "diamonds", "clubs"];
    
    public $index;

    public function render()
    {
        return view('livewire.card');
    }
}
