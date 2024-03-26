<header class="top-0 pt-5 left-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
    <span class="text-md text-gray-500 sm:text-center dark:text-gray-400">
        <a href="/home">RM SYLLABUS</a>
    </span>
    @guest
    <span class="text-md text-gray-500 sm:text-center dark:text-gray-400 ml-auto">
        <a href="/login">Login</a>
    </span>
    @endguest
    @auth
    <div class="ml-auto">
        <x-filament-panels::user-menu />
    </div>
    @endauth
</header>