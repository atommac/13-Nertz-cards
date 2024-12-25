<x-app-layout>
    <x-slot name="header">
        <nav class="flex space-x-4 p-4 bg-gray-100 dark:bg-gray-800">
            <livewire:add-team />
            <button class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-500 transition duration-150 ease-in-out">
                About
            </button>
            <button class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-500 transition duration-150 ease-in-out">
                Services
            </button>
            <button class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-500 transition duration-150 ease-in-out">
                Contact
            </button>
        </nav>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:scoreboard />
        </div>
    </div>
</x-app-layout>

