<header class="top-0 pt-5 left-0 z-20 w-full p-4 bg-darkblue border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6">
    
    @unless(request()->is('home'))
        <h5 class="text-white"><a href="/home">RM SYLLABUS</a></h5>
    @endunless

    <div class="flex items-center ml-auto">
        <div class="dropdown mr-4" x-data="{open: false}">
            <h5 class="text-white"> 
                <a
                    class="dropdown-toggle"
                    role="button"
                    aria-expanded="false"
                    x-on:click="open = !open"
                >
                    Change Language
                </a>
            </h5>

            <div
                class="dropdown-menu"
                x-show="open"
                x-transition.origin.top.left
                x-on:click.outside="open = false"
                style="display:none"
            >
                <a class="dropdown-item text-white" href="{{ URL::current() . '?locale=en' }}">English</a>
                <a class="dropdown-item text-white" href="{{ URL::current() . '?locale=es' }}">Español</a>
                <a class="dropdown-item text-white" href="{{ URL::current() . '?locale=fr' }}">Français</a>
            </div>
            </div>
        </div>

        @guest
            <h5 class="text-white mr-4"><a href="/login">Login</a></h5>
        @endguest

        @auth
            <x-filament-panels::user-menu />
        @endauth

    </div>

</header>