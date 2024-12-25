<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Player;

class AddPlayerModal extends Component
{
    public $isOpen = false;
    public $teamIndex = null;
    public $playerIndex = null;
    public $newPlayerName = null;

    protected $rules = [
        'newPlayerName' => 'required|min:1|max:20',
    ];

    #[On('openAddPlayerModal')] 
    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function addPlayer()
    {
        $this->validate();
        $newPlayer = Player::create([
            'name' => $this->newPlayerName,
            'user_id' => Auth::id(),
        ]);
        $this->closeModal();
        $this->dispatch('addPlayer');

        // $this->dispatch('addNewPlayer',['newPlayerName' => $this->newPlayerName, 'teamIndex' => $this->teamIndex, 'playerIndex' => $this->playerIndex]);
        
    }

    public function render()
    {
        return view('livewire.add-player-modal');
    }
}
