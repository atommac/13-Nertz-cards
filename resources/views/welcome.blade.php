<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', '13NertzCards') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>

    <body class="font-sans antialiased bg-zinc-900 dark:text-white/50 flex items-center justify-center min-h-screen p-4">

        {{-- <body class="flex items-center justify-center h-screen bg-gray-100"> --}}

            <div class="text-center w-full max-w-md mx-auto">
            
                <!-- Heading Section -->
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-gray-500 mb-2">
                        13 <span class="text-red-500">Nertz</span> Cards
                    </h1> 
                    <h1 class="text-lg sm:text-xl font-bold text-gray-500 mb-6 sm:mb-8">
                        An Online Nertz Scorecard
                    </h1>               

                    <img src="img/card.png" alt="13NertzCards Logo" class="mx-auto mb-6 sm:mb-8 w-48 sm:w-64 h-auto">
            
                <!-- Logo Section -->
                <div class="flex flex-col sm:flex-row justify-center items-center gap-6">
                <!-- Links Section -->
                    <livewire:sign-in-link />
                    <livewire:register-link />
                </div>
                {{-- <div class="py-10 text-lg">
                    <a class="hover:text-white">Skip</a>
                </div> --}}
                
           
                
            </div>
        
        {{-- </body> --}}
    </body>
</html>
