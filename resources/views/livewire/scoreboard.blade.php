<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-6">
        <!-- Header Labels -->
        <div class="hidden sm:grid sm:grid-cols-6 gap-4 mb-0">
            <div class="col-span-2 text-center text-xl text-black dark:text-gray-400">Team</div>
            <div class="col-span-4 text-center text-xl text-black dark:text-gray-400">Score</div>
        </div>

        <!-- Teams and Scores -->
        <div class="sm:grid sm:grid-cols-6 sm:gap-4 sm:pt-4">
            @foreach ($teams as $index => $team)
                <!-- Team and Score Group -->
                <div class="flex flex-col gap-3 mb-12 sm:mb-0 sm:contents">
                    <!-- Team Section -->
                    <div class="h-28 sm:h-auto sm:col-span-2 px-2">
                        <div class="h-full rounded-lg bg-orange-400 shadow p-4 flex items-center space-x-4">  
                            <div class="flex-shrink-0 scale-75 sm:scale-100 origin-left">
                                <livewire:card :index=$index :key=$index />
                            </div>
                            <div class="flex-grow">
                                @foreach ($team['playerNames'] as $name)
                                <div><span class="text-xl font-bold">                                    
                                    {{ $name }}
                                </span></div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Score Section -->
                    <div class="h-28 sm:h-auto sm:col-span-4 px-2">
                        <div class="h-full rounded-lg bg-teal-400 shadow flex justify-between overflow-hidden relative">
                            <div class="flex items-center px-1 sm:px-4 py-4 overflow-x-auto pr-[130px]" x-ref="scoreContainer{{ $index }}">
                                @for ($i = 0; $i < $hand; $i++)
                                    @if (!isset($teams[$index]['scores'][$i]))                       
                                        <div wire:click="openScoreInputModal({{ $i }}, {{ $index }})" 
                                             class="relative flex-shrink-0 w-[82px] h-[112px] scale-[0.7] sm:scale-100 -ml-4 sm:ml-2 first:ml-0 bg-gray-100 dark:bg-gray-800 hover:bg-gray-600 border-2 border-black rounded-lg shadow-lg flex items-center justify-center">
                                            <div class="absolute top-1 left-3 text-md font-medium text-black dark:text-gray-400">
                                                {{ $i+1 }}
                                            </div>
                                            <div class="text-4xl font-bold text-gray-800 dark:text-white">
                                                ?
                                            </div>
                                        </div>
                                    @elseif (isset($teams[$index]['scores'][$i])) 
                                        <div wire:click="openScoreInputModal({{ $i }}, {{ $index }})" 
                                             class="{{ $teams[$index]['nertz'][$i] === true ? 'border-red-500' : 'border-black' }} relative flex-shrink-0 w-[82px] h-[112px] scale-[0.7] sm:scale-100 -ml-4 sm:ml-2 first:ml-0 bg-gray-100 dark:bg-gray-800 border-2 rounded-lg shadow-lg flex items-center justify-center">
                                            <div class="absolute top-1 left-3 text-md font-medium text-black dark:text-gray-400">
                                                {{ $i+1 }}
                                            </div>
                                            <div class="text-4xl font-bold text-gray-800 text-black dark:text-gray-400">
                                                {{ $teams[$index]['scores'][$i] }}
                                            </div>
                                            @if ($teams[$index]['nertz'][$i] === true) 
                                                <div class="absolute bottom-1 text-md font-medium text-red-500">
                                                    Nertz!
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endfor
                            </div>

                            <div class="flex items-center p-0 sm:pr-4 bg-teal-400 absolute right-0 top-0 h-full">
                                <svg @click="$refs.scoreContainer{{ $index }}.scrollLeft = $refs.scoreContainer{{ $index }}.scrollWidth" class="flex-shrink-0 fill-gray-600 scale-50 sm:scale-75 -ml-1 cursor-pointer hover:fill-gray-800" fill="#000000" width="50px" height="50px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier"><path d="M5.536 21.886a1.004 1.004 0 0 0 1.033-.064l13-9a1 1 0 0 0 0-1.644l-13-9A1 1 0 0 0 5 3v18a1 1 0 0 0 .536.886z"></path></g>
                                </svg>
                                <div class="relative flex-shrink-0 w-[82px] h-[112px] scale-[0.7] sm:scale-100 bg-gray-100 dark:bg-gray-800 border-2 border-black rounded-lg shadow-lg flex items-center justify-center">
                                    <div class="absolute top-1 left-3 text-md font-medium text-black dark:text-gray-400">
                                        Total
                                    </div>
                                    <div class="text-4xl font-bold text-gray-800 text-black dark:text-gray-400">
                                        @if (isset($teams[$index]['total']))  
                                            {{ $teams[$index]['total'] }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Card Display Section -->
    <div class="relative w-[100px] h-[150px] mx-auto mt-10">
        <div class="absolute left-7 bottom-5 rotate-12 w-[82px] h-[112px] bg-white dark:bg-gray-100 border-2 border-black rounded-lg shadow-lg flex items-center justify-center">
            <!-- Small number in the top-left corner -->
            <div class="absolute top-1 left-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                <img class="h-[25px]" src="/img/hearts.svg"">
            </div>
            <!-- Large centered number -->
            <div class="text-4xl font-bold text-gray-800 dark:text-white">
            <img class="h-[60px]" src="/img/hearts.svg">
            </div>
            <div class="absolute bottom-1 right-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                <img class="h-[25px] rotate-180" src="/img/hearts.svg">
            </div>
        </div>
        <div class="absolute w-[82px] h-[112px] bg-white dark:bg-gray-100 border-2 border-black rounded-lg shadow-lg flex items-center justify-center">
            <!-- Small number in the top-left corner -->
            <div class="absolute top-1 left-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                <img class="h-[25px]" src="/img/spades.svg"">
            </div>
            <!-- Large centered number -->
            <div class="text-4xl font-bold text-gray-800 dark:text-white">
            <img class="h-[60px]" src="/img/spades.svg">
            </div>
            <div class="absolute bottom-1 right-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                <img class="h-[25px] rotate-180" src="/img/spades.svg">
            </div>
        </div>
    </div>

    <div class="flex items-center justify-center text-gray-500 dark:text-gray-400 text-xl font-medium align-center">
        13 Nertz Cards
    </div>

    <div class="container mx-auto p-8">
        @livewire('score-input-modal')
    </div>
</div>

