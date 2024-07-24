<?php
use App\Filament\App\Pages\Download;
use App\Filament\App\Pages\CompetencyFramework;
?>

<div class="container-fluid w-full flex flex-wrap bg-darkblue justify-center pb-5">

    <div class="w-full px-20">
        <h1 class="text-white pt-6">Your pathway:</h1>
        <p class="text-white pt-4">{{ $this->getRecord()->name }}</p>
    </div>

    <div class="flex justify-center flex-col sm:flex-row mt-10 mb-5 px-20">
        <a href="{{ url(CompetencyFramework::getUrl()) }}" class="flex items-center mb-3 sm:mb-0 mr-20">
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
        </a>
    </div>



</div>

