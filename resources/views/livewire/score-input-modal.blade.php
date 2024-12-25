<div>
    <!-- Modal Background Overlay -->
    <div class="fixed inset-0 bg-gray-800 bg-opacity-50 z-40" 
         x-show="isOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         wire:ignore.self 
         x-data="{ isOpen: @entangle('isOpen') }" 
         @click.self="isOpen = false">
    </div>

    <!-- Modal Box -->
    <div class="fixed inset-0 flex items-center justify-center z-50" x-show="isOpen" x-data="{ isOpen: @entangle('isOpen') }">
        <div class="bg-white dark:bg-gray-800 p-6 w-96 rounded-lg shadow-lg">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Input Score</h2>

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabs -->
            <div class="flex border-b border-gray-200 dark:border-gray-700 mb-4">
                <button wire:click="setActiveTab('simple')" 
                        class="flex-1 py-2 px-4 text-sm font-medium hover:text-blue-500 {{ $activeTab === 'simple' ? 'border-b-2 border-blue-500 text-blue-500' : '' }}">
                    Simple Score
                </button>
                <button wire:click="setActiveTab('calculated')" 
                        class="flex-1 py-2 px-4 text-sm font-medium hover:text-blue-500 {{ $activeTab === 'calculated' ? 'border-b-2 border-blue-500 text-blue-500' : '' }}">
                    Calculated Score
                </button>
            </div>

            <!-- Simple Score Tab -->
            <div class="{{ $activeTab === 'simple' ? '' : 'hidden' }} text-gray-700 dark:text-gray-300">
                <!-- Total Score Input -->
                <div class="mb-4">
                    <label for="totalScore" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Score</label>
                    <input type="number" id="totalScore" wire:model.fill="totalScore" autofocus
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-400 dark:focus:border-blue-400 focus:outline-none transition duration-150 ease-in-out"
                           placeholder="Enter total score">
                    @error('totalScore') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Nertz Checkbox -->
                <div class="flex items-center mb-4">
                    <input type="checkbox" id="hasNertz" wire:model.fill="hasNertz"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-400 dark:border-gray-600"
                           @if($otherTeamHasNertz && !$nertz) disabled @endif>
                    <label for="hasNertz" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Player has Nertz (zero Nertz cards)
                        @if ($otherTeamHasNertz && !$nertz)
                            <span class="text-red-500 ml-2">(Team {{ $nertzTeamIndex + 1 }} already has Nertz)</span>
                        @endif
                    </label>
                </div>
            </div>

            <!-- Calculated Score Tab -->
            <div class="{{ $activeTab === 'calculated' ? '' : 'hidden' }}">
                <!-- Number of Nertz Cards -->
                <div class="mb-4">
                    <label for="nertzCards" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nertz Cards Remaining
                        @if ($otherTeamHasNertz && !$nertz)
                            <span class="text-red-500 ml-2">(Team {{ $nertzTeamIndex + 1 }} already has Nertz)</span>
                        @endif
                    </label>
                    <input type="number" id="nertzCards" wire:model.fill="nertzCards" autofocus placeholder="Nertz Cards"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-400 dark:focus:border-blue-400 focus:outline-none transition duration-150 ease-in-out">
                    @error('nertzCards') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Number of Points -->
                <div class="mb-4">
                    <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Points</label>
                    <input type="number" id="points" wire:model.fill="points"
                           class="w-full p-2 mt-1 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                    @error('points') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="flex justify-end space-x-2 mt-4">
                <button @click="isOpen = false" wire:click="closeModal" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200">
                    Cancel
                </button>
                <button wire:click="submitScore" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-600">
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>