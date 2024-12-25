<div class="relative">
    <!-- Text Input -->
    <label for="team-{{ $teamIndex }}-player-{{ $playerIndex }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Player {{ $playerIndex + 1 }}
    </label>
    <input 
        type="text"
        wire:model.live="query"
        wire:keydown.arrow-up="navigateUp"
        wire:keydown.arrow-down="navigateDown"
        wire:keydown.enter="selectHighlighted"
        class="w-full p-2 border rounded"
        placeholder="Search or add a player..."
    />

    <!-- Suggestions Dropdown -->
    @if (!empty($players) || $allowAddNew)
        <ul class="absolute z-10 w-full bg-white border rounded shadow-md mt-1">
            @foreach ($players as $index => $player)
                <li
                    wire:click="selectPlayer({{ $player['id'] }})"
                    class="p-2 cursor-pointer {{ $highlightIndex === $index ? 'bg-blue-500 text-white' : '' }}"
                    wire:mouseover="highlightIndex = {{ $index }}"
                >
                    {{ $player['name'] }}
                </li>
            @endforeach

            @if ($allowAddNew)
                <li
                    wire:click="addNewPlayer"
                    class="p-2 cursor-pointer text-green-500 {{ $highlightIndex === count($players) ? 'bg-blue-500 text-white' : '' }}"
                    wire:mouseover="highlightIndex = {{ count($players) }}"
                >
                    Add "{{ $query }}" as a new player
                </li>
            @endif
        </ul>
    @endif
</div>