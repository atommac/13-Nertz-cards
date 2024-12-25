<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Player;
use App\Models\Game;


class Setup extends Component
{
    public $numTeams = 2;
    public $teams = []; // Array to store team information
    public $game;
    public $newTeam; 
    public $players = [];
    public $query = ''; // User input
    public $highlightIndex = 0; // For navigating results
    public $allowAddNew = false; // Show "Add New Player" option
    public $selectedPlayerName = [];
    public $selectedPlayerId = [];
 

    protected $rules = [
        'teams.*.selectedPlayer.*' => 'required|min:5|max:20',
    ];


    public function mount()
    {
        // Clear any existing game state
        if (Auth::check()) {
            \App\Models\User::where('id', Auth::id())
                ->update(['scoreboard_state' => null]);
            session()->forget(['teams', 'hand']);
        }

        $this->game = new Game;
        $this->game->save();

        $this->initializeTeams();
    }

    public function updatedTeams($value, $nested)
    {
        $nestedData = explode(".", $nested);
        $this->teams = $this->teams; // Force reactivity
    }

    public function updatedNumTeams()
    {
        // Reinitialize teams based on the updated number of teams
        $this->initializeTeams();
    }

    public function updated($property, $value)
    {
        if (preg_match('/^selectedPlayerName\.(\d+)\.(\d+)$/', $property, $matches))
        {
            $teamId = $matches[1];
            $playerId = $matches[2];
            
            // Get all selected player IDs
            $selectedPlayersList = collect($this->selectedPlayerId)
                ->flatten()
                ->map(function ($player) {
                    return ['id' => $player];
                });
            $modifiedSelectedPlayersList = $selectedPlayersList->whereNotNull('id');
            
            // Get all selected names (for user/friend name filtering)
            $selectedNames = collect($this->selectedPlayerName)
                ->flatten()
                ->filter()
                ->values()
                ->toArray();
            
            if (strlen($value) > 1) {
                // Get the authenticated user
                $user = Auth::user();
                
                // Get all players from user and their friends
                $playersQuery = Player::where(function ($query) use ($user, $value) {
                    // User's own players
                    $query->where('user_id', $user->id)
                        ->where('name', 'like', '%' . $value . '%')
                    // Union with players shared with the user
                    ->orWhereIn('id', function($subquery) use ($user) {
                        $subquery->select('player_id')
                            ->from('shared_players')
                            ->where('shared_with_user_id', $user->id);
                    })
                    ->where('name', 'like', '%' . $value . '%');
                })
                ->whereNotIn('id', $modifiedSelectedPlayersList)
                ->orderBy('name', 'asc')
                ->take(10)
                ->get();

                // Get user and friends whose names match the search and haven't been selected
                $userAndFriends = collect([$user])
                    ->merge($user->friends)
                    ->filter(function ($person) use ($value, $selectedNames) {
                        return stripos($person->name, $value) !== false && 
                               !in_array($person->name, $selectedNames);
                    })
                    ->map(function ($person) {
                        return [
                            'id' => null,  // These will be treated as new players when selected
                            'name' => $person->name
                        ];
                    });

                // Combine players and user/friend names, limiting to 10 total results
                $this->players[$teamId][$playerId] = $userAndFriends
                    ->merge($playersQuery)
                    ->take(10)
                    ->toArray();

                // Check if the entered name is an exact match
                $exactMatch = $playersQuery->contains('name', $value) || 
                             $userAndFriends->contains('name', $value);
                $this->allowAddNew = !$exactMatch;
            } else {
                // Clear the list if the query is empty
                $this->players = [];
                $this->allowAddNew = false;
            }
        }
    }


    public function initializeTeams()
    {
        $this->teams = array_slice($this->teams, 0, $this->numTeams);

        for ($i = 0; $i < $this->numTeams; $i++) {
            if (!isset($this->teams[$i])) {
                $this->teams[$i] = [
                    'numPlayers' => 1, // Default to 1 player
                    'selectedPlayers' => [null], // Default empty slots
                    'playerNames' => [null]
                ];
            } else {
                // Preserve existing selected players without reordering
                // $this->teams[$i]['selectedPlayers'] = array_pad(
                //     $this->teams[$i]['selectedPlayers'],
                //     $this->teams[$i]['numPlayers'],
                //     $this->teams[$i]['playerNames'],
                //     null
                // );
                $selectedPlayerIds = collect($this->teams)
                ->pluck('selectedPlayers')
                ->flatten()
                ->map(function ($player) {
                return ['id' => $player];
                });

                // $teams[$i]['playerNames'] = ['hello','hi'];

            }
        }
    }

    public function selectHighlighted($teamIndex, $playerIndex)
    {
        // Check if there are players and if a valid highlight index exists
        if (!empty($this->players[$teamIndex][$playerIndex]) && isset($this->players[$teamIndex][$playerIndex][$this->highlightIndex])) 
        {
            // Select the highlighted player
            // $this->selectPlayer($this->players[$teamIndex][$playerIndex][$this->highlightIndex]['id']);
            
            $this->teams[$teamIndex]['selectedPlayers'][$playerIndex] = $this->players[$teamIndex][$playerIndex][$this->highlightIndex]['name'];
            $this->selectedPlayerId[$teamIndex][$playerIndex] = $this->players[$teamIndex][$playerIndex][$this->highlightIndex]['id'];
            $this->selectedPlayerName[$teamIndex][$playerIndex] = $this->players[$teamIndex][$playerIndex][$this->highlightIndex]['name'];
            $this->players[$teamIndex][$playerIndex] = null;
        } 
        elseif ($this->allowAddNew) 
        {
            // If no player is highlighted but "Add New Player" is allowed, add a new player
            $this->addNewPlayer($teamIndex, $playerIndex);
        }
    }

    public function navigateDown($teamIndex, $playerIndex)
    {
        if ($this->highlightIndex < count($this->players[$teamIndex][$playerIndex]) - 1) 
        {
            // Move to the next player in the list
            $this->highlightIndex++;
        } 
        elseif ($this->allowAddNew && $this->highlightIndex === count($this->players[$teamIndex][$playerIndex]) - 1) 
        {
            // Move to the "Add New Player" option
            $this->highlightIndex++;
        }
    }

    public function navigateUp($teamIndex, $playerIndex)
    {
        if ($this->highlightIndex < count($this->players[$teamIndex][$playerIndex]) + 1) {
            // Move to the next player in the list
            $this->highlightIndex--;
        }
        // } elseif ($this->allowAddNew && $this->highlightIndex === count($this->players[$teamIndex][$playerIndex]) - 1) {
        //     // Move to the "Add New Player" option
        //     $this->highlightIndex--;
        // }
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

    public function getAvailablePlayers($teamIndex, $playerIndex)
    {


    }     
    
    public function addNewPlayer($teamId, $playerId)
    {
        $this->validate();

        // Get the authenticated user
        $user = Auth::user();

        // Check if the name matches the user or any of their friends
        $matchingPerson = collect([$user])
            ->merge($user->friends)
            ->first(function ($person) use ($teamId, $playerId) {
                return $person->name === $this->selectedPlayerName[$teamId][$playerId];
            });

        if ($matchingPerson) {
            // Create a new player with the matched name
            $newPlayer = Player::create([
                'name' => $matchingPerson->name,
                'user_id' => Auth::id(),
            ]);
        } else {
            // Create a new player with the entered name
            $newPlayer = Player::create([
                'name' => $this->selectedPlayerName[$teamId][$playerId],
                'user_id' => Auth::id(),
            ]);
        }

        $this->selectedPlayerName[$teamId][$playerId] = $newPlayer['name'];
        $this->selectedPlayerId[$teamId][$playerId] = $newPlayer['id'];
        $this->players[$teamId][$playerId] = null;
    }

    #[On('addPlayer')]
    public function addPlayer()
    {
        $this->teams = $this->teams; // Force reactivity
    }

    public function updateAfterNewPlayer($team,$player,$id)
    {
        $this->teams[$team]['selectedPlayers'][$player] = $id;

        // Trigger state change explicitly
        $this->teams = array_values($this->teams);

    }

    public function submit()
    {
        $game = new Game;

        $this->validate();

        foreach ($this->teams as $index => $team)
        {
            $selectedPlayersList = $this->selectedPlayerId[$index];
            $playerNames = [];
            
            foreach ($selectedPlayersList as $key => $playerId) {
                if ($playerId) {
                    // If it's an existing player, get their name from the database
                    $player = Player::find($playerId);
                    if ($player) {
                        $playerNames[] = $player->name;
                    }
                } else {
                    // If it's a new player (user or friend name), get the name from selectedPlayerName
                    $playerNames[] = $this->selectedPlayerName[$index][$key];
                }
            }
            
            $this->teams[$index]['playerNames'] = $playerNames;
        }

        // Clear any existing state before setting new state
        if (Auth::check()) {
            \App\Models\User::where('id', Auth::id())
                ->update(['scoreboard_state' => null]);
        }
        session()->forget(['teams', 'hand']);
        session(['teams' => $this->teams]); // Store new teams data in session

        $this->redirectRoute('scoreboard');
    }

    public function openAddPlayerModal()
    {
        $this->dispatch('openAddPlayerModal');
    }

    public function render()
    {
        return view('livewire.setup');
    }
}
