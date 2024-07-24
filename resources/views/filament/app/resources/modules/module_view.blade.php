<?php
use App\Filament\App\Resources\PathwayResource;
?>

<x-filament-panels::page>
<div class="bg-white">
    <div class="container-fluid w-full flex flex-wrap bg-darkblue justify-center pb-5">

        <!-- module name -->
        <div class="w-full px-20">
            <h1 class="text-white pt-6">Module</h1>
            <h2 class="text-white">{{ $this->getRecord()->name }}</h2>
        </div>

        
        <div class="flex flex-wrap w-full pt-5 px-20">

            <!-- time estimate -->
            <div class="w-full md:w-1/3 py-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="w-6 h-6 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <p class="text-white">Estimated time to complete: {{ $this->getRecord()->time_estimate }} hours</p>
            </div>

            <!-- completion status -->
            @auth
            <div class="w-full md:w-1/3 py-4 flex items-center justify-center">
                @if ($this->getRecord()->completion_status === 'Not Started')
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                @elseif ($this->getRecord()->completion_status === 'In Progress')
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                @elseif ($this->getRecord()->completion_status === 'Completed')
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />                </svg>
                @endif
                <p class="text-center text-white">{{ $this->getRecord()->completion_status }}</p>
            </div>
            @endauth

            <!-- next module -->
            @if(!$this->lastInPathway())
                <div class="w-full md:w-1/3 py-4 flex items-center flex items-center justify-end">
                    <a href="{{ $this->nextModuleUrl() }}" class="mr-4">
                        <button class="button-white button-small text-blue inline-flex items-center justify-center">
                            Next module
                            <svg class="w-6 h-6 ml-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </a>
                </div>
            @endif

        </div>

    </div>

    <div class="bg-white pt-10">
        <div class="px-20">

            <!-- return to pathway -->
            <a href="{{ PathwayResource::getUrl('view', ['record' => $this->parent]) }}" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="var(--stats4sd)" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <h4 class="text-red ml-1">Return to pathway</h4>
            </a>
        
            <!-- module info -->
            <div class="px-20 mt-10">

                <!-- description -->
                <h5 class="text-black">{{ $this->getRecord()->description }}</h5>

                <!-- previous modules -->
                @unless($this->firstInPathway() === 1)
                    @if($this->parent->order_required === 1)
                        <div class="flex items-center border-b pb-10 mb-10">
                            
                            <div class="mt-10 mr-20">
                                <img src="/images/previous.png" alt="previous-image" class="w-24 h-auto">
                            </div>
                            
                            <div>
                                <h4>Before completing this module, you should be familiar with the contents of the following modules:</h4>
                                    <ul class="list-disc list-inside space-y-2 pt-2">
                                        @foreach($this->previousModules() as $previousModule)
                                            <li>{{ $previousModule->name }}</li>
                                        @endforeach
                                    </ul>
                            </div>

                        </div>
                    @endif
                @endunless

                <!-- competencies -->
                <div class="flex items-center mt-10 mb-20">
                        
                        <div class="mr-20">
                            <img src="/images/competencies.png" alt="competencies-image" class="w-24 h-auto">
                        </div>
                        
                        <div>
                            <h4>This module is linked to the following competencies:</h4>
                                <ul class="list-disc list-inside space-y-2 pt-2">
                                    @foreach($this->getRecord()->competencies as $competency)
                                        <li>{{ $competency->name }}</li>
                                    @endforeach
                                </ul>
                        </div>

                </div>

            </div>

        </div>
    </div>

    <!-- activities banner -->
    <div class="container-fluid w-full flex flex-wrap bg-darkblue justify-center pb-5">
        <div class="w-full px-20">
            <h3 class="text-white pt-6">Activities</h3>
        </div>
    </div>

</div>

<div class="bg-lightgrey">

    <!-- infolist -->
    {{ $this->infolist }}

    <!-- next module -->
    @if(!$this->lastInPathway() && $this->getRecord()->completion_status === 'Completed')
        <div class="flex items-center justify-center pt-5">
            <a href="{{ $this->nextModuleUrl() }}" class="mr-4">
                <button class="button-red button-small inline-flex items-center justify-center">
                    Next module
                    <svg class="w-6 h-6 ml-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </a>
        </div>
    @endif

</div>

</x-filament-panels::page>