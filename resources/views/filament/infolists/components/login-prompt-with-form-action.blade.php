<div class="content-center">

    <div class="relative flex items-center justify-center text-center mb-3">
        <div class="absolute border-t border-gray-200 w-full h-px"></div>
        <p class="inline-block relative bg-white text-sm p-2 rounded-full font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-100">
            Login via
        </p>
    </div>

    <x-filament-socialite::buttons :show-divider="false"/>

    <div class="grid grid-cols-1 gap-4 my-4">
        <x-filament::button
            :color="'gray'"
            :outlined="true"
            :icon="'heroicon-o-envelope'"
            tag="a"
            :href="\Filament\Facades\Filament::getLoginUrl()"
        >
            Email
        </x-filament::button>
    </div>

        <div class="relative flex items-center justify-center text-center mt-4">
            <div class="absolute border-t border-gray-200 w-full h-px"></div>
            <p class="inline-block relative bg-white text-sm p-2 rounded-full font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-100">
                Or
            </p>
        </div>
    </div>
