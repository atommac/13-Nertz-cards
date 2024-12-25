<?php

namespace App\Livewire;



use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Player;
use Auth;

class SearchPlayer extends Component
{
    public $query = ''; // User input
    public $players = []; // Filtered players
    public $highlightIndex = 0; // For navigating results
    public $allowAddNew = false; // Show "Add New Player" option
    public $teamIndex;
    public $playerIndex;


    public function updatedQuery()
    {
        if (strlen($this->query) > 0) {
            // Fetch matching players
            $this->players = Player::where('name', 'like', '%' . $this->query . '%')
                ->orderBy('name', 'asc')
                ->take(10)
                ->get()
                ->toArray();

            // Check if the entered name is an exact match
            $this->allowAddNew = !Player::where('name', $this->query)->exists();
        } else {
            // Clear the list if the query is empty
            $this->players = [];
            $this->allowAddNew = false;
        }
    }

    public function selectHighlighted()
    {
        // Check if there are players and if a valid highlight index exists
        if (!empty($this->players) && isset($this->players[$this->highlightIndex])) {
            // Select the highlighted player
            $this->selectPlayer($this->players[$this->highlightIndex]['id']);
        } elseif ($this->allowAddNew) {
            // If no player is highlighted but "Add New Player" is allowed, add a new player
            $this->addNewPlayer();
        }
    }

    public function navigateDown()
    {
        if ($this->highlightIndex < count($this->players) - 1) {
            // Move to the next player in the list
            $this->highlightIndex++;
        } elseif ($this->allowAddNew && $this->highlightIndex === count($this->players) - 1) {
            // Move to the "Add New Player" option
            $this->highlightIndex++;
        }
    }

    public function navigateUp()
    {
        if ($this->highlightIndex < count($this->players) - 1) {
            // Move to the next player in the list
            $this->highlightIndex--;
        } elseif ($this->allowAddNew && $this->highlightIndex === count($this->players) - 1) {
            // Move to the "Add New Player" option
            $this->highlightIndex--;
        }
    }

    public function selectPlayer($playerId)
    {
        // Set the selected player's name to the query
        $player = Player::find($playerId);

        if ($player) {
            $this->query = $player->name;
            $this->players = []; // Clear suggestions
            $this->allowAddNew = false;
        }
    }

    public function addNewPlayer()
    {
        // Create a new player with the current query
        $player = Player::create(['name' => $this->query, 'user_id' => Auth::id()]);

        // Set the new player's name to the query
        $this->query = $player->name;
        $this->players = [];
        $this->allowAddNew = false;

        // Emit an event to notify other components if needed
        $this->emit('playerAdded', $player->id);
    }

    public function render()
    {
        return view('livewire.search-player');
    }
}