<?php
use App\Filament\App\Pages\Download;
use App\Filament\App\Pages\CompetencyFramework;
?>

<div class="container-fluid w-full flex flex-wrap bg-darkblue justify-center pb-5">

    <div class="w-full px-20">
        <h1 class="text-white pt-6">Your learning pathway:</h1>
        <p class="text-white text-xl pt-4">{{ $this->getRecord()->name }}</p>
    </div>

    <div class="flex justify-center flex-col sm:flex-row mt-10 mb-5 px-20">
        <!-- <a href="{{ url(CompetencyFramework::getUrl()) }}" class="flex items-center mb-3 sm:mb-0 mr-20">
            <button class="button-white button-wide text-blue inline-flex items-center justify-center">
                View competency framework
            </button>
        </a>
        <a href="{{ Download::getUrl() }}" class="flex items-center button-wrapper">
            <button class="button-darkblue button-wide text-white inline-flex items-center justify-center">
                <img src="/images/download.png" alt="Icon" class="w-6 h-6 mr-3 default-icon">
                <img src="/images/download_blue.png" alt="Hovered Icon" class="w-6 h-6 mr-3 hover-icon hidden">
                <span class="default-icon">Download my pathway (PDF)</span>
                <span class="hover-icon hidden">Download my pathway (PDF)</span>
            </button>
        </a> -->
    </div>

    <div class="container-fluid w-full bg-white px-20 py-8">
        <div class="flex justify-between items-start pb-4">
            <div></div> 
            <a href="{{ \App\Filament\App\Resources\PathwayResource::getUrl('index') }}">
                <button class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition">
                    Change pathway
                </button>
            </a>
        </div>
        <h3 class="text-black">Pathway description</h3>
        <div class="divider mt-3"></div>
        <div class="text-black">
            {{ $this->getRecord()->description }}
        </div>
    </div>

    <div class="w-full bg-darkblue px-20 mt-2 py-2">
        <p class="text-white text-xl font-semibold">Modules</p>
    </div>


</div>

