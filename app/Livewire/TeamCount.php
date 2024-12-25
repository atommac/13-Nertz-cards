<?php

// app/Http/Livewire/TeamCount.php
namespace App\Livewire;

use Livewire\Component;

class TeamCount extends Component
{
    public $teamcount = 0;

    public function increaseTeams()
    {
        $this->teamcount++;
        $this->emit('teamsUpdated', $this->teamcount); // Emit an event with the new value
    }

    public function render()
    {
        return view('livewire.team-count');
    }
}