<!-- Wrapper for vertical centering -->
<div class="min-h-screen flex items-start sm:items-center pt-6 sm:py-12">
    <!-- Main container with responsive width -->
    <div class="w-full max-w-lg mx-4 sm:mx-auto">
        <!-- Game setup form -->
        <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <h2 class="text-xl sm:text-2xl font-bold text-center text-gray-800 dark:text-gray-200 mb-4 sm:mb-6">Game Setup</h2>

            <!-- Form to choose number of teams -->
            <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                <label for="numTeams" class="block text-sm sm:text-base text-gray-700 dark:text-gray-300 font-medium mb-2">Number of Teams (2-4)</label>
                <select id="numTeams" wire:model.change="numTeams" class="w-full p-2 text-sm sm:text-base border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-2 focus:ring-blue-500">
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>

            <!-- Team Setup -->
            @foreach ($teams as $teamIndex => $team)
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700 pt-2">
                <h3 class="text-base sm:text-md font-semibold text-gray-800 dark:text-gray-200 mb-3">
                    Team {{ $teamIndex + 1 }}
                </h3>

                <!-- Number of Players Selection -->
                <label for="team-{{ $teamIndex }}-numPlayers" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Number of Players
                </label>
                <select id="team-{{ $teamIndex }}-numPlayers" 
                        wire:model.change="teams.{{ $teamIndex }}.numPlayers" 
                        class="w-full p-2 text-sm sm:text-base border mb-4 mt-1 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-2 focus:ring-blue-500">
                    <option value="1">1 Player</option>
                    <option value="2">2 Players</option>
                </select>

                <!-- Player Selections -->
                @for ($playerIndex = 0; $playerIndex < $team['numPlayers']; $playerIndex++)
                <div class="relative mb-4">
                    <label for="selectedPlayerName-{{ $teamIndex }}-{{ $playerIndex }}" 
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Player {{ $playerIndex + 1 }}
                    </label>
                    <div class="flex justify-between">
                        <input 
                            type="text"
                            wire:model.live="selectedPlayerName.{{ $teamIndex }}.{{ $playerIndex }}"
                            wire:key="selectedPlayerName-{{ $teamIndex }}-{{ $playerIndex }}"
                            wire:keydown.up="navigateUp({{ $teamIndex }}, {{ $playerIndex }})"
                            wire:keydown.down="navigateDown({{ $teamIndex }}, {{ $playerIndex }})"
                            wire:keydown.enter="selectHighlighted({{ $teamIndex }}, {{ $playerIndex }})"
                            class="w-full p-2 text-sm sm:text-base border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-2 focus:ring-blue-500"
                            placeholder="Search or add a player..."
                        />

                        <input 
                            type="hidden" 
                            wire:model="selectedPlayerId.{{ $teamIndex }}.{{ $playerIndex }}"
                            wire:key="selectedPlayerId-{{ $teamIndex }}-{{ $playerIndex }}"
                        />

                        <!-- Suggestions Dropdown -->
                        @if (isset($players[$teamIndex][$playerIndex]))
                            <ul class="absolute z-10 w-full border rounded-md shadow-lg mt-11 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 max-h-48 overflow-y-auto">
                                @foreach ($players[$teamIndex][$playerIndex] ?? [] as $index => $player)
                                    <li
                                        value="{{ $player['id'] }}"
                                        wire:click="selectHighlighted({{ $teamIndex }}, {{ $playerIndex }})"
                                        class="p-2 text-sm cursor-pointer hover:bg-blue-500 dark:hover:bg-gray-600 {{ $highlightIndex === $index ? 'bg-blue-500 text-white' : '' }}"
                                        wire:mouseenter="$set('highlightIndex', {{ $index }})"
                                    >
                                        {{ $player['name'] }}
                                    </li>
                                @endforeach

                                @if ($allowAddNew)
                                    <li
                                        wire:click="addNewPlayer({{ $teamIndex }},{{ $playerIndex }})"
                                        class="p-2 text-sm cursor-pointer hover:bg-blue-500 dark:hover:bg-gray-600 text-green-500 {{ $highlightIndex === count($players[$teamIndex][$playerIndex]) ? 'bg-blue-500 text-white' : '' }}"
                                        wire:mouseenter="$set('highlightIndex', {{ count($players[$teamIndex][$playerIndex]) }})"
                                    >
                                        Add <span class="text-black dark:text-gray-200 font-bold">{{ $selectedPlayerName[$teamIndex][$playerIndex] ?? 'None' }}</span> as a new player
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>  
                @endfor
            </div>
            @endforeach

            <!-- Submit Button -->
            <button wire:click="submit" 
                    class="w-full py-3 text-white bg-gray-500 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-500 transition duration-150 ease-in-out text-sm sm:text-base">
                Start Game
            </button>
            
            <div class="mt-4">
                @livewire('add-player-modal')
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('updateAfterNewPlayer', (event) => {
        const { team, player, id } = event.detail;
        Livewire.emit('updateAfterNewPlayer', team, player, id);
    });
</script>
