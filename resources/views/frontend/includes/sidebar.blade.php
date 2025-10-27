<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div class="p-3 border-b min-h-[105px]">
        <a href="#" class="">
            <img src="{{ getLogo() }}" class="light-logo" alt="Logo">
        </a>
    </div>
    <div class="sidebar-menu-area flex flex-col justify-between">
        <ul class="sidebar-menu flex flex-col gap-8" id="sidebar-menu">
            @php
                $module = trim(session('current_module'));
            @endphp

            @if (view()->exists("frontend.menus.$module"))
                @include("frontend.menus.$module")
            @else
                <li><a href="#">Default Menu</a></li>
            @endif

          
            <hr>
            <li>
                <a href="#">
                    <svg class="pe-2" width="36" height="30" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span class="text-lg">{{ __db('profile') }}</span>
                </a>
            </li>
            </li>
            <li>
                <a href="{{ route('web.logout') }}">
                    <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                    </svg>
                    <span class="text-lg">{{ __db('logout') }}</span>
                </a>
            </li>
        </ul>
    </div>
</aside>