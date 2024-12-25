<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class Scoreboard extends Component
{
    public $teams = [];
    public $scores = [];
    public $hand = 1;
    

    public function mount()
    {
        if (!Auth::check()) {
            $this->redirectRoute('login');
            return;
        }

        $user = Auth::user();
        
        // Try to get data from user's saved state first
        if ($user->scoreboard_state) {
            $savedState = json_decode($user->scoreboard_state, true);
            $this->teams = $savedState['teams'] ?? [];
            $this->hand = $savedState['hand'] ?? 1;
            // Update session with the loaded state
            session(['teams' => $this->teams]);
            session(['hand' => $this->hand]);
        } 
        // Fall back to session data if no saved state exists
        else if (session('teams') !== null) {
            $this->teams = session('teams', []);
            $this->hand = session('hand', 1);
            // Save the session data to user's state
            $this->saveStateToUser();
        }
        // If no data exists anywhere, redirect to setup
        else {
            $this->redirectRoute('setup');
        }
    }

    private function saveStateToUser()
    {
        if (Auth::check()) {
            $state = [
                'teams' => $this->teams,
                'hand' => $this->hand
            ];
            Auth::user()->update([
                'scoreboard_state' => json_encode($state)
            ]);
        }
    }

    #[On('nextHand')]
    public function updateHand($data)
    {
        $this->teams[$data['team']]['scores'][$data['hand']] = $data['score'];
        $this->teams[$data['team']]['nertz'][$data['hand']] = $data['nertz'];

        $this->updateTotalScore();

        // Only increment hand if this is not an edit and all teams have scores for this hand
        if (!$data['isEdit']) {
            $count = 0;
            foreach ($this->teams as $id => $team) {
                if (isset($team['scores'][$data['hand']])) {
                    $count++;
                }
            }
            if ($count === count($this->teams)) {
                $this->hand++;
            }
        }
        
        // Update both session and user state
        session(['teams' => $this->teams]); 
        session(['hand' => $this->hand]);
        $this->saveStateToUser();
    }

    public function updateTotalScore()
    {
        foreach ($this->teams as $id => $team) {
            if (isset($team['scores']))
            {
                if (isset($this->teams[$id]['total']))
                {
                    $this->teams[$id]['total'] = 0;
                }
                foreach ($team['scores'] as $hand => $score)
                {
                    if (isset($this->teams[$id]['total']))
                    {
                        $this->teams[$id]['total'] += $score;
                    }
                    else
                    {
                        $this->teams[$id]['total'] = $score;
                    } 
                }
            }
        }
    }

    #[On('team-added')] 
    public function addTeam()
    {
        $this->teams[] = [
            ['players' => ['Player 3'], 'scores' => [], 'total' => 0]
        ];
        $this->saveStateToUser();
    }

    public function addScore($teamIndex, $score)
    {
        // Add the new score to the team's score array
        $this->teams[$teamIndex]['scores'][] = $score;

        // Recalculate the total score
        $this->teams[$teamIndex]['total'] = array_sum($this->teams[$teamIndex]['scores']);
        
        $this->saveStateToUser();
    }

    public function clearState()
    {
        if (Auth::check()) {
            Auth::user()->update(['scoreboard_state' => null]);
            session()->forget(['teams', 'hand']);
            $this->teams = [];
            $this->hand = 1;
        }
    }

    public function openScoreInputModal($id, $index)
    {
        $data = [
            'id' => $id,
            'index' => $index,
            'isEdit' => isset($this->teams[$index]['scores'][$id]),
        ];

        if ($data['isEdit']) {
            $data['score'] = $this->teams[$index]['scores'][$id];
            $data['nertz'] = $this->teams[$index]['nertz'][$id];
        }

        // Check if any other team has Nertz for this hand
        $otherTeamHasNertz = false;
        $nertzTeamIndex = null;
        foreach ($this->teams as $teamIndex => $team) {
            if ($teamIndex !== $index && 
                isset($team['nertz'][$id]) && 
                $team['nertz'][$id] === true) {
                $otherTeamHasNertz = true;
                $nertzTeamIndex = $teamIndex;
                break;
            }
        }

        $data['otherTeamHasNertz'] = $otherTeamHasNertz;
        $data['nertzTeamIndex'] = $nertzTeamIndex;

        $this->dispatch('openScoreModal', $data);
    }

    public function render()
    {
        return view('livewire.scoreboard');
    }
}