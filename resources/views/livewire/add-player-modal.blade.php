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
        <div class="bg-white dark:bg-gray-800 border-2 p-6 w-96 rounded-lg shadow-lg">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Add Player</h2>

            <!-- Player Name -->
            <div class="mb-4">
                <label for="newPlayerName" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Add a new player to your list:</label>
                <input type="text" id="newPlayerName" wire:model.fill="newPlayerName" autofocus placeholder="Name"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-400 dark:focus:border-blue-400 focus:outline-none transition duration-150 ease-in-out"
                @error('newPlayerName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Modal Actions -->
            <div class="flex justify-end space-x-2">
                <button @click="isOpen = false" wire:click="closeModal" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200">
                    Cancel
                </button>
                <button wire:click="addPlayer" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-600">
                    Add to Player List
                </button>
            </div>
        </div>
    </div>
</div>