<?php
use App\Filament\App\Pages\Info;
use App\Filament\App\Resources\PathwayResource;
?>

<x-filament-panels::page>

<div class="py-10 container-fluid w-full flex flex-wrap bg-darkblue justify-center">

    <div class="w-full sm:w-1/2 px-20">
        <h1 class="text-white pt-6">Research Methods <br>Syllabus</h1>
        <p class="text-white pt-4">Your journey to build research methods <br>capacity and knowledge.</p>
    </div>

    <div class="w-full sm:w-1/2 flex justify-center items-center">
        <img src="/images/syllabus_header.png" alt="Image" class="max-w-xs h-auto">
    </div>
    

    <div class="flex justify-center flex-col sm:flex-row mt-10 mb-5 px-20">
        <a href="{{ url(PathwayResource::getUrl('view', ['record' => 'essential-research-methods-for-agroecology'])) }}" class="flex items-center mb-3 sm:mb-0 mr-20">
            <button class="button-white text-blue inline-flex items-center justify-center">
                Begin learning 
                <svg class="w-6 h-6 ml-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </a>
        <a href="{{ url(Info::getUrl()) }}" class="flex items-center button-wrapper">
            <button class="button-darkblue text-white inline-flex items-center justify-center">
                <span class="default-icon">Find out more</span>
                <span class="hover-icon hidden">Find out more</span>
                <img src="/images/lightbulb.png" alt="Icon" class="w-6 h-6 ml-3 default-icon">
                <img src="/images/lightbulb_blue.png" alt="Hovered Icon" class="w-6 h-6 ml-3 hover-icon hidden">
            </button>
        </a>
    </div>

</div>

<div class="mt=5 px-20">
    <h2>What is the RM Syllabus?</h2>
    <div class="divider mt-3"></div>
</div>

<div class="container px-20">
    <div class="section flex items-center mb-20">
        <div class="mr-auto flex items-center ml-20">
            <img src="images/about1.png" alt="Competency Framework Image">
            <div>
                <h4 class="ml-8">Competency Framework</h3>
                <div class="max-w-md ml-8">
                    <p>
                        We've set out what we believe to be the essential research methods skills that are needed to conduct high-quality research in agroecology.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="section flex items-center mb-20">
        <div class="ml-auto flex items-center mr-20">
            <img src="images/about2.png" alt="Learning Pathway Image" class="mr-4">
            <div>
                <h4 class="ml-8">Learning Pathway</h3>
                <div class="max-w-md ml-8">
                    <p>
                        We provide a clear pathway towards developing these skills. You can create a free account to keep track of your progress.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="section flex items-center mb-20">
        <div class="mr-auto flex items-center ml-20">
            <img src="images/about3.png" alt="Modules Image" class="mr-4">
            <div>
                <h4 class="ml-8">Modules</h3>
                <div class="max-w-md ml-8">
                    <p>
                        The pathway is structured into modules which you can follow like an online course. The topics and module aims are mapped to the essential skills we identified.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="section flex items-center mb-20">
        <div class="ml-auto flex items-center mr-20">
            <img src="images/about4.png" alt="Learning Materials Image" class="mr-4">
            <div>
                <h4 class="ml-8">High Quality learning materials</h3>
                <div class="max-w-md ml-8">
                    <p>
                        Develop your skills and learn about research methods using a range of materials including videos, books and interactive e-learning. Activities in the syllabus modules are hand-selected from our extensive 
                        <a href="https://stats4sd.org/resources" target="_blank" class="text-red">resources database</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center flex-col sm:flex-row pt-20 mb-5 px-20">
        <a href="{{ url(PathwayResource::getUrl('view', ['record' => 'essential-research-methods-for-agroecology'])) }}" class="flex items-center mb-3 sm:mb-0 mr-20">
            <button class="button-red text-blue inline-flex items-center justify-center">
                Begin learning 
                <svg class="w-6 h-6 ml-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </a>
        <a href="{{ url(Info::getUrl()) }}" class="flex items-center button-wrapper">
            <button class="button-white-outline text-white inline-flex items-center justify-center">
                <span class="default-icon">Find out more</span>
                <span class="hover-icon hidden">Find out more</span>
                <img src="/images/lightbulb_blue.png" alt="Icon" class="w-6 h-6 ml-3 default-icon">
                <img src="/images/lightbulb.png" alt="Hovered Icon" class="w-6 h-6 ml-3 hover-icon hidden">
            </button>
        </a>
    </div>

</div>


</x-filament-panels::page>
