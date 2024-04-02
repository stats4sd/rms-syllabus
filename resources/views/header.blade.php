<header class="top-0 pt-5 left-0 z-20 w-full p-4 bg-darkblue border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
        <h5 class="text-white"><a href="/home">RM SYLLABUS</a></h5>
    @guest
        <h5 class="text-white ml-auto"><a href="/login">Login</a></h5>
    @endguest
    @auth
    <div class="ml-auto">
        <x-filament-panels::user-menu />
    </div>
    @endauth
</header>