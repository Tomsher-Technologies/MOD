<header id="header"
    class="@if (Route::currentRouteName() == 'home') top-0 w-full py-6 bg-transparent z-[999] @else w-full py-6 z-[999] bg-[#ebebea] @endif fixed top-0 w-full z-[999] shadow-md transition-all ease-in-out duration-500 opacity-0"
    style="transition: background-color 0.3s ease;">
    <div class="container mx-auto">
        <div class="flex items-center justify-between">
            <!-- Logo Section -->
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}">
                    <img src="{{ getModuleEventLogo() }}" class="h-[60px]" alt="Main Logo">
                </a>
                <a href="{{ route('home') }} ">
                    <img src="{{ asset('assets/img/md-logo.svg') }}" class="h-[70px]" alt="Main Logo">
                </a>
            </div>

            <x-countdown />

            <!-- Navigation Links -->
            <div class="flex items-center gap-6 text-md">
                <ul class="flex items-center gap-6">
                    <li>
                        <a href="{{ route('news') }}" class="hover:text-[#b68a35]">{{ __db('news_events') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('committees') }}" class="hover:text-[#b68a35]">{{ __db('committees') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('about-us') }}" class="hover:text-[#b68a35]">{{ __db('about_us') }}</a>
                    </li>
                </ul>
            </div>

            <!-- Login Button -->
            <div class="flex items-center">
                <a href="{{ route('login') }}"
                    class="inline-block bg-[#b68a35] text-white px-8 py-2 rounded-lg hover:bg-[#9e7526]">
                    {{ __db('login') }}
                </a>
            </div>
        </div>
    </div>
</header>

<style>
    /* Smooth fade-in for the header */
    #header {
        opacity: 1;
        transition: opacity 1.5s ease-out;
        /* Smooth fade-in transition */
    }

    /* Apply gradient animation for the countdown timer only */
    @keyframes countdownGradient {
        0% {
            background: linear-gradient(90deg, #9e7526, #b68a35);
        }

        50% {
            background: linear-gradient(90deg, #b68a35, #9e7526);
        }

        100% {
            background: linear-gradient(90deg, #9e7526, #b68a35);
        }
    }

    .animate-gradient {
        animation: countdownGradient 6s ease-in-out infinite;
        /* Smoother gradient animation for countdown timer */
    }

    /* Smooth fade-in effect for header */
    window.onload=function() {
        document.getElementById('header').style.opacity='1';
    }

    /* Smooth transitions for elements */
    body {
        transition: all 0.3s ease-in-out;
    }

    /* Add shadow to header */
    header {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Smooth hover effect for navigation links */
    .hover\:text-[#b68a35]:hover {
        color: #b68a35;
        transition: color 0.3s ease-in-out;
    }

    /* Smooth hover effect for login button */
    .hover\:bg-[#9e7526]:hover {
        background-color: #9e7526;
        transition: background-color 0.3s ease-in-out;
    }

    /* Change header background on scroll */
    .scrolled {
        background-color: white !important;
        /* Change to white on scroll */
    }
</style>

<script>
    // Detect scroll and add class to change header background color
    window.onscroll = function() {
        let header = document.getElementById('header');
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
</script>
