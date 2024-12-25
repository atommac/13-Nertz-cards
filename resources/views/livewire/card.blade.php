<div class="relative w-[82px] h-[112px] bg-white dark:bg-gray-100 border-2 border-black rounded-lg shadow-lg flex items-center justify-center">
    <!-- Small number in the top-left corner -->
    <div class="absolute top-1 left-1 text-sm font-medium text-gray-500 dark:text-gray-400">
        <img class="h-[25px]" src="/img/{{ $suit[$index] }}.svg">
    </div>
    <!-- Large centered number -->
    <div class="text-4xl font-bold text-gray-800 dark:text-white">
       <img class="h-[60px]" src="/img/{{ $suit[$index] }}.svg">
    </div>
    <div class="absolute bottom-1 right-1 text-sm font-medium text-gray-500 dark:text-gray-400">
        <img class="h-[25px] rotate-180" src="/img/{{ $suit[$index] }}.svg">
    </div>
</div>