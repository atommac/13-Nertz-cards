<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card1 extends Component
{

    public $suit = [];
    public $index;
    /**
     * Create a new component instance.
     */

    public function mount()
    {
        $this->initializeSuits();
    }
    
    private function initializeSuits()
    {
        $suit = [
            0 => "spades",
            1 => "hearts",
            2 => "diamonds",
            3 => "clubs"
        ];

    }

    public function __construct()
    {
    
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card1');
    }
}
