<header id="header"
    class="@if (Route::currentRouteName() == 'home') top-0 w-full py-6 bg-transparent z-[999] @else w-full py-6 z-[999] bg-[#ebebea] @endif fixed top-0 w-full z-[999] shadow-md transition-all ease-in-out duration-500 opacity-0"
    style="transition: background-color 0.3s ease;">
    
    @php
        $floorPlans = getFloorPlansFormatted();
    @endphp

    <div class="container mx-auto">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}">
                    <img src="{{ getModuleEventLogo() }}" class="h-[60px]" alt="Main Logo">
                </a>
                <a href="{{ route('home') }} ">
                    <img src="{{ getLogo() }}" class="h-[70px]" alt="Main Logo">
                </a>
            </div>

            <x-countdown />

            <div class="flex items-center gap-6 text-md">
                <ul class="flex items-center gap-6">
                    <li class="relative" id="floor-plans-dropdown">
                        <a href="javascript:void(0)" class="font-bold hover:text-[#b68a35]" id="floor-plans-toggle">
                            {{ __db('floor_plans') }}
                        </a>
                        <div id="floor-plans-menu"
                            class="absolute left-0 mt-1 w-64 bg-white rounded-md shadow-xl py-1 z-[100] hidden">
                            @if($floorPlans->isEmpty())
                                <div class="px-4 py-2 text-gray-600">
                                    {{ __db('no_floor_plans_available') }}
                                </div>
                            @else
                                @foreach($floorPlans as $floorPlan)
                                    <div class="floor-plan-group">
                                        <div class="floor-plan-title font-bold px-4 py-2 text-gray-800 hover:bg-gray-100 cursor-pointer flex justify-between items-center">
                                            {{ app()->getLocale() === 'ar' ? $floorPlan['title_ar'] : $floorPlan['title_en'] }}
                                            <svg class="w-4 h-4 ml-2 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        <div class="floor-plan-files hidden bg-gray-50">
                                            @if(!empty($floorPlan['file_objects']) && count($floorPlan['file_objects']) > 0)
                                                @foreach($floorPlan['file_objects'] as $fileObj)
                                                    @php
                                                        $path = is_string($fileObj) ? $fileObj : ($fileObj['path'] ?? '');
                                                        $title = '';
                                                        
                                                        if (is_array($fileObj)) {
                                                            if (app()->getLocale() === 'ar' && !empty($fileObj['title_ar'])) {
                                                                $title = $fileObj['title_ar'];
                                                            } else {
                                                                $title = $fileObj['title_en'] ?? $fileObj['title'] ?? basename($path);
                                                            }
                                                        } else {
                                                            $title = basename($path);
                                                        }
                                                    @endphp
                                                    <a href="{{ asset('storage/' . $path) }}" 
                                                       target="_blank" 
                                                       class="block px-8 py-2 text-sm text-gray-700 hover:bg-gray-200">
                                                        {{ $title }}
                                                    </a>
                                                @endforeach
                                            @else
                                                <div class="px-8 py-2 text-sm text-gray-500">
                                                    {{ __db('no_files_available') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('news') }}"
                            class="font-bold hover:text-[#b68a35] {{ request()->routeIs('news') || request()->routeIs('news-details') ? 'text-[#b68a35]' : '' }}">
                            {{ __db('news_events') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('committees') }}"
                            class="font-bold hover:text-[#b68a35] {{ request()->routeIs('committees') ? 'text-[#b68a35]' : '' }}">
                            {{ __db('committees') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about-us') }}"
                            class="font-bold hover:text-[#b68a35] {{ request()->routeIs('about-us') ? 'text-[#b68a35]' : '' }}">
                            {{ __db('about_us') }}
                        </a>
                    </li>
                </ul>

                <div class="relative" id="language-switcher-container">
                    <button type="button" id="language-toggle-button"
                        class="flex items-center text-gray-700 hover:text-[#b68a35] transition-colors duration-300">
                        <svg class="w-6 h-6" enable-background="new 0 0 511.992 511.992" height="512"
                            viewBox="0 0 511.992 511.992" width="512" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path d="m195.472 250.163h-64.558c-1.989 0-3.897.79-5.303 2.197l-50.782 50.782c-.126.126-.269.27-.621.124s-.352-.348-.352-.526v-45.076c0-4.142-3.358-7.5-7.5-7.5h-8.07c-9.214 0-16.709-7.496-16.709-16.709v-201.746c0-9.213 7.496-16.709 16.709-16.709h201.744c9.214 0 16.709 7.496 16.709 16.709v137.186c0 4.142 3.358 7.5 7.5 7.5s7.5-3.358 7.5-7.5v-137.186c.001-17.484-14.224-31.709-31.708-31.709h-201.745c-17.484 0-31.709 14.225-31.709 31.709v201.745c0 17.484 14.225 31.709 31.709 31.709h.57v37.576c0 6.32 3.772 11.966 9.611 14.385 1.938.803 3.965 1.193 5.974 1.193 4.043 0 8.008-1.583 10.994-4.568l48.585-48.585h61.452c4.142 0 7.5-3.358 7.5-7.5s-3.357-7.501-7.5-7.501z"></path>
                                <path d="m453.705 193.675h-201.744c-17.485 0-31.709 14.225-31.709 31.709v201.744c0 17.485 14.225 31.709 31.709 31.709h126.01l48.585 48.585c2.986 2.986 6.95 4.568 10.994 4.568 2.008 0 4.037-.391 5.974-1.193 5.839-2.418 9.611-8.065 9.611-14.385v-37.576h.57c17.485 0 31.709-14.225 31.709-31.709v-201.743c.001-17.485-14.224-31.709-31.709-31.709zm16.71 233.453c0 9.214-7.496 16.709-16.709 16.709h-8.07c-4.142 0-7.5 3.358-7.5 7.5v45.076c0 .179 0 .381-.352.526-.351.147-.495.003-.621-.124l-50.782-50.782c-1.406-1.407-3.314-2.197-5.303-2.197h-129.117c-9.214 0-16.709-7.496-16.709-16.709v-201.743c0-9.214 7.496-16.709 16.709-16.709h201.744c9.214 0 16.709 7.496 16.709 16.709v201.744z"></path>
                                <path d="m169.056 63.267c-.026-.069-.053-.138-.081-.206-1.644-3.994-5.494-6.573-9.812-6.573-.003 0-.007 0-.011 0-4.322.004-8.172 2.592-9.809 6.593-.023.056-.045.113-.067.17l-51.512 135.254c-1.474 3.871.468 8.204 4.339 9.678.878.335 1.781.493 2.668.493 3.022 0 5.871-1.84 7.01-4.833l10.99-28.855h72.415l4.79 12.694c1.463 3.875 5.788 5.832 9.665 4.369 3.875-1.462 5.832-5.79 4.369-9.665zm-40.572 96.722 30.661-80.502 30.38 80.502z"></path>
                                <path d="m388.564 385.905c-7.962 6.225-17.527 9.515-27.661 9.515-24.788 0-44.954-20.166-44.954-44.954s20.166-44.954 44.954-44.954h20.174c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5c0 0-23.864 0-24.209 0-9.213 0-16.709-7.496-16.709-16.709s7.496-16.709 16.709-16.709c4.747 0 9.287 2.031 12.457 5.571 2.762 3.086 7.504 3.348 10.59.585s3.348-7.504.585-10.59c-6.011-6.715-14.625-10.566-23.632-10.566-17.484 0-31.709 14.225-31.709 31.709 0 8.899 3.69 16.948 9.614 22.713-20.002 9.728-33.823 30.254-33.823 53.95 0 33.059 26.895 59.954 59.954 59.954 13.514 0 26.274-4.391 36.899-12.697 3.263-2.551 3.841-7.265 1.29-10.528-2.552-3.263-7.265-3.841-10.529-1.29z"></path>
                            </g>
                        </svg>
                    </button>
                    <div id="language-menu"
                        class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-xl py-1 z-[100] hidden">
                        <a href="{{ route('lang.switch', ['lang' => 'en']) }}"
                            class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 hover:text-[#b68a35]">English</a>
                        <a href="{{ route('lang.switch', ['lang' => 'ar']) }}"
                            class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 hover:text-[#b68a35]">العربية</a>
                    </div>
                </div>

                <a href="{{ route('login') }}"
                    class="inline-block bg-[#b68a35] text-white px-8 py-2 rounded-lg hover:bg-[#9e7526]">
                    {{ __db('login') }}
                </a>
            </div>
        </div>
    </div>
</header>

<style>
    #header {
        opacity: 1;
        transition: opacity 1.5s ease-out;
    }

    @keyframes countdownGradient {
        0% { background: linear-gradient(90deg, #9e7526, #b68a35); }
        50% { background: linear-gradient(90deg, #b68a35, #9e7526); }
        100% { background: linear-gradient(90deg, #9e7526, #b68a35); }
    }

    .animate-gradient {
        animation: countdownGradient 6s ease-in-out infinite;
    }

    body {
        transition: all 0.3s ease-in-out;
    }

    header {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .hover\:text-[#b68a35]:hover {
        color: #b68a35;
        transition: color 0.3s ease-in-out;
    }

    .hover\:bg-[#9e7526]:hover {
        background-color: #9e7526;
        transition: background-color 0.3s ease-in-out;
    }

    .scrolled {
        background-color: white !important;
    }

    .floor-plan-title svg {
        transition: transform 0.2s ease;
    }

    .floor-plan-title.active svg {
        transform: rotate(180deg);
    }
</style>

<script>
    window.onscroll = function() {
        let header = document.getElementById('header');
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Language Switcher Logic
        const langToggleButton = document.getElementById('language-toggle-button');
        const langMenu = document.getElementById('language-menu');

        if (langToggleButton && langMenu) {
            langToggleButton.addEventListener('click', function(event) {
                event.stopPropagation();
                langMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                if (!langMenu.classList.contains('hidden') && !langToggleButton.contains(event.target)) {
                    langMenu.classList.add('hidden');
                }
            });
        }

        // Floor Plans Dropdown Logic
        const floorPlansToggle = document.getElementById('floor-plans-toggle');
        const floorPlansMenu = document.getElementById('floor-plans-menu');
        const floorPlansDropdown = document.getElementById('floor-plans-dropdown');

        if (floorPlansToggle && floorPlansMenu && floorPlansDropdown) {
            let hoverTimeout;

            floorPlansDropdown.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
                floorPlansMenu.classList.remove('hidden');
            });

            floorPlansDropdown.addEventListener('mouseleave', function() {
                hoverTimeout = setTimeout(function() {
                    floorPlansMenu.classList.add('hidden');
                }, 300);
            });

            floorPlansMenu.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
            });

            floorPlansMenu.addEventListener('mouseleave', function() {
                hoverTimeout = setTimeout(function() {
                    floorPlansMenu.classList.add('hidden');
                }, 300);
            });
        }

        // Floor Plan Toggle Logic
        const floorPlanTitles = document.querySelectorAll('.floor-plan-title');
        
        floorPlanTitles.forEach(title => {
            title.addEventListener('click', function() {
                const filesContainer = this.nextElementSibling;
                filesContainer.classList.toggle('hidden');
                this.classList.toggle('active');
            });
        });
    });
</script>
