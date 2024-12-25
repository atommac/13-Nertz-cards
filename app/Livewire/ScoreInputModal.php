<?php

// app/Livewire/ScoreInputModal.php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 

class ScoreInputModal extends Component
{
    public $isOpen = false; // Controls modal visibility
    public $nertzCards = 0; // Number of Nertz cards
    public $points = 0;     // Points scored
    public $totalScore = 0;    // For simple scoring method
    public $hasNertz = false;  // For simple scoring method
    public $hand;
    public $team;
    public $nertz;
    public $score;
    public $activeTab = 'simple';
    public $isEdit = false;
    public $otherTeamHasNertz = false;
    public $nertzTeamIndex = null;

    protected $rules = [
        'nertzCards' => 'required|integer|min:0',
        'points' => 'required|integer',
        'totalScore' => 'required|integer'
    ];

    public function mount()
    {
        $this->resetScoring();
    }

    private function resetScoring()
    {
        $this->nertzCards = 0;
        $this->points = 0;
        $this->totalScore = 0;
        $this->hasNertz = false;
        $this->nertz = false;
        $this->score = 0;
        $this->isEdit = false;
        $this->otherTeamHasNertz = false;
        $this->nertzTeamIndex = null;
    }

    #[On('openScoreModal')] 
    public function openModal($data)
    {
        $this->hand = $data['id'];
        $this->team = $data['index'];
        $this->isEdit = $data['isEdit'] ?? false;
        $this->otherTeamHasNertz = $data['otherTeamHasNertz'] ?? false;
        $this->nertzTeamIndex = $data['nertzTeamIndex'];

        if ($this->isEdit) {
            $this->totalScore = $data['score'];
            $this->hasNertz = $data['nertz'];
            $this->score = $data['score'];
            $this->nertz = $data['nertz'];

            // For calculated method, we need to reverse calculate the values
            if ($this->nertz) {
                // If they got Nertz (zero cards), then points = score
                $this->nertzCards = 0;
                $this->points = $data['score'];
            } else {
                // If they didn't get Nertz, we need to solve: score = points - (2 * nertzCards)
                // Let's assume they had 1 Nertz card if they didn't get Nertz
                $this->nertzCards = 1;
                $this->points = $data['score'] + (2 * $this->nertzCards);
            }
        }

        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetScoring();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function submitScore()
    {
        // Check if trying to set Nertz when another team already has it
        if (($this->activeTab === 'simple' && $this->hasNertz) || 
            ($this->activeTab === 'calculated' && $this->nertzCards === 0)) {
            if ($this->otherTeamHasNertz && !$this->nertz) {
                session()->flash('error', 'Team ' . ($this->nertzTeamIndex + 1) . ' already has Nertz for this hand.');
                return;
            }
        }

        if ($this->activeTab === 'simple') {
            $this->validate(['totalScore' => 'required|integer']);
            $this->score = $this->totalScore;
            $this->nertz = $this->hasNertz;
        } else {
            $this->validate([
                'nertzCards' => 'required|integer|min:0',
                'points' => 'required|integer'
            ]);
            $this->score = $this->points - (2 * $this->nertzCards);
            $this->nertz = ($this->nertzCards === 0);
        }
           
        $this->dispatch('nextHand', [
            'hand' => $this->hand, 
            'team' => $this->team, 
            'score' => $this->score, 
            'nertz' => $this->nertz,
            'isEdit' => $this->isEdit
        ]);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.score-input-modal');
    }
}