<?php
use App\Filament\App\Pages\Download;

// dd($getRecord()->getNextRecordUrl());


?>

<div class="container-fluid w-full flex flex-wrap bg-darkblue justify-center pb-5">

    <div class="w-full px-20">
        <h1 class="text-white pt-6">Module</h1>
        <h2 class="text-white">{{ $getRecord()->name }}</h2>
    </div>

</div>

<div class="container-fluid w-full flex flex-wrap bg-darkblue pb-5 px-20">

    <div class="flex flex-wrap w-full">

        <div class="w-full md:w-1/3 py-4 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="w-6 h-6 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <p class="text-white">Estimated time to complete: {{ $getRecord()->time_estimate }} hours</p>
        </div>

        @auth
        <div class="w-full md:w-1/3 py-4 flex items-center justify-center">
            <p class="text-center text-white">Status</p>
        </div>
        @endauth

        <div class="w-full md:w-1/3 py-4 flex items-center flex items-center justify-end">
            <a href="#" class="mr-4">
                <button class="button-white button-small text-blue inline-flex items-center justify-center">
                    Continue learning
                    <svg class="w-6 h-6 ml-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </a>
        </div>

    </div>

</div>