<div class="bg-white p-4 rounded-md shadow-2xl mb-4 flex justify-between items-start mx-auto border-l-8 border-darkblue">
    <div>
        <span class="text-sm font-semibold">PATHWAY</span>
        
        <h2 class="text-xl font-bold text-stats4sd mt-1">{{ $getRecord()->name }}</h2>
        
        <p class="text-gray-700 mt-2">{{ $getRecord()->description }}</p>
    </div>

    <div class="flex items-center space-x-2">
        <a href="{{ \App\Filament\App\Resources\PathwayResource::getUrl('view', ['record' => $getRecord()->slug]) }}">
            <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                View
            </button>
        </a>
    </div>
</div>
