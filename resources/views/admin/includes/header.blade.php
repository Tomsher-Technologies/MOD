<div class="navbar-header border-b border-neutral-200 pe-0 !py-[31px]">
    <div class="flex items-center justify-between">
        <div class="col-auto me-auto">
            <img src="{{ getAdminEventLogo() }}" alt="" width="150">
        </div>

        <div class="col-auto ms-auto flex items-center gap-4">
            @php
                $events = getAllEvents();
                $currentEventId = session('current_event_id', $events->first()?->id ?? null);
            @endphp

            <form method="POST" action="{{ route('events.setCurrentEvent') }}" id="currentEventForm" class="inline-block">
                @csrf
                <select name="event_id" id="current-event-select"
                    class="p-2 rounded border border-neutral-300 text-neutral-700 cursor-pointer"
                    onchange="document.getElementById('currentEventForm').submit();" title="{{ __db('select_event') }}">
                    @foreach ($events as $event)
                        <option value="{{ $event->id }}" {{ $currentEventId == $event->id ? 'selected' : '' }}>
                            {{ $event->code }} - {{ $event->name_en }}
                            @if ($event->is_default)
                                ({{ __db('default') }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </form>

            @php
                $currentRoute = Route::currentRouteName();

                $buttonConfig = [
                    'roles.index' => [
                        'text' => __db('add_new_role'),
                        'link' => route('roles.create'),
                        'permission' => 'add_role',
                    ],
                    'staffs.index' => [
                        'text' => __db('add_new_staff'),
                        'link' => route('staffs.create'),
                        'permission' => 'add_staff',
                    ],
                    'events.index' => [
                        'text' => __db('add_new_event'),
                        'link' => route('events.create'),
                        'permission' => 'add_event',
                    ],
                    'other-interview-members.index' => [
                        'text' => __db('add_new_member'),
                        'link' => route('other-interview-members.create'),
                        'permission' => 'add_other_interview_members',
                    ],
                    'delegations.index' => [
                        'text' => __db('add_new_delegation'),
                        'link' => route('delegations.create'),
                        'permission' => 'add_delegations',
                    ],
                    'escorts.index' => [
                        'text' => __db('add_escort'),
                        'link' => route('escorts.create'),
                        // 'permission' => 'add_escorts',
                        'permission' => 'add_delegations',
                        
                    ],
                ];
            @endphp

            @if (isset($buttonConfig[$currentRoute]) && auth()->user()->can($buttonConfig[$currentRoute]['permission']))
                <a href="{{ $buttonConfig[$currentRoute]['link'] }}"
                    class="btn me-8 text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">
                    <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14m-7 7V5" />
                    </svg>
                    <span>{{ $buttonConfig[$currentRoute]['text'] }}</span>
                </a>
            @endif
        </div>
        <div class="col-auto ml-4">
            <div class="flex flex-wrap items-center gap-6">

                <button id="languageToggleBtn" data-dropdown-toggle="languageDropdown"
                    class="flex items-center justify-center h-10 w-10 rounded-full bg-neutral-200 hover:bg-neutral-300">
                    🌐
                </button>

                <div id="languageDropdown"
                    class="hidden absolute right-0 z-20 mt-2 w-36 rounded-lg bg-white shadow-lg border border-gray-200">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="languageToggleBtn">
                        @php
                            $languages = getAllActiveLanguages();
                        @endphp
                        @foreach ($languages as $lang)
                            <li>
                                <a href="{{ route('lang.switch', ['lang' => $lang->code]) }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white {{ app()->getLocale() == $lang->code ? 'bg-gray-200 font-semibold' : 'hover:bg-gray-100' }}">
                                    {{ $lang->name }}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>

                <!-- Message Dropdown Start  -->
                <button data-modal-target="default-modal" data-modal-toggle="default-modal"
                    class="has-indicator flex h-10 w-10 items-center justify-center rounded-full bg-neutral-200 "
                    type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span
                        class="absolute top-3 -end-[8px] -translate-y-1/2 px-1 py-0.5 leading-[1] flex items-center text-sm justify-center badge rounded-full bg-danger-600 text-white">01</span>
                </button>
                <!-- Message Dropdown End  -->
                <!-- Notification Start  -->
                <button data-dropdown-toggle="dropdownNotification"
                    class="has-indicator flex h-10 w-10 items-center justify-center rounded-full bg-neutral-200 "
                    type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <span
                        class="absolute top-3 -end-[8px] -translate-y-1/2 px-1 py-0.5 leading-[1] flex text-sm items-center justify-center badge rounded-full bg-danger-600 text-white">05</span>
                </button>
                <div id="dropdownNotification"
                    class="z-10 hidden w-full max-w-[394px] overflow-hidden rounded-2xl bg-white shadow-lg ">
                    <div
                        class="m-4 flex items-center justify-between gap-2 rounded-lg bg-primary-50 px-4 py-2 dark:bg-primary-600/25">
                        <h6 class="mb-0 text-lg font-semibold text-neutral-900">
                            Notification
                        </h6>
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white font-bold text-primary-600 dark:bg-neutral-600 dark:text-white">05</span>
                    </div>
                    <div class="scroll-sm !border-t-0">
                        <div class="max-h-[400px] overflow-y-auto">
                            <a href="javascript:void(0)"
                                class="flex justify-between gap-1 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <h6 class="fw-semibold mb-1 text-sm">Congratulations</h6>
                                        <p class="mb-0 line-clamp-1 text-sm">
                                            Your profile has been Verified. Your profile has been
                                            Verified
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span class="text-sm text-neutral-500">23 Mins ago</span>
                                </div>
                            </a>
                            <a href="javascript:void(0)"
                                class="flex justify-between gap-1 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <h6 class="fw-semibold mb-1 text-sm">Ronald Richards</h6>
                                        <p class="mb-0 line-clamp-1 text-sm">
                                            You can stitch between artboards
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span class="text-sm text-neutral-500">23 Mins ago</span>
                                </div>
                            </a>
                            <a href="javascript:void(0)"
                                class="flex justify-between gap-1 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <h6 class="fw-semibold mb-1 text-sm">Arlene McCoy</h6>
                                        <p class="mb-0 line-clamp-1 text-sm">
                                            Invite you to prototyping
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span class="text-sm text-neutral-500">23 Mins ago</span>
                                </div>
                            </a>
                            <a href="javascript:void(0)"
                                class="flex justify-between gap-1 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <h6 class="fw-semibold mb-1 text-sm">Annette Black</h6>
                                        <p class="mb-0 line-clamp-1 text-sm">
                                            Invite you to prototyping
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span class="text-sm text-neutral-500">23 Mins ago</span>
                                </div>
                            </a>
                            <a href="javascript:void(0)"
                                class="flex justify-between gap-1 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <h6 class="fw-semibold mb-1 text-sm">Darlene Robertson</h6>
                                        <p class="mb-0 line-clamp-1 text-sm">
                                            Invite you to prototyping
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span class="text-sm text-neutral-500">23 Mins ago</span>
                                </div>
                            </a>
                        </div>
                        <div class="px-4 py-2 text-center">
                            <a href="notifications.html"
                                class="text-center font-semibold text-primary-600 hover:underline dark:text-primary-600">See
                                All Notification
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Notification End  -->
                <div id="dropdownProfile" class="dropdown-menu-sm z-10 hidden rounded-lg bg-white p-3 shadow-lg ">
                    <div
                        class="mb-4 flex items-center justify-between gap-2 rounded-lg bg-primary-50 px-4 py-2 dark:bg-primary-600/25">
                        <div>
                            <h6 class="mb-0 text-lg font-semibold text-neutral-900">
                                Robiul Hasan
                            </h6>
                            <span class="text-neutral-500">Admin</span>
                        </div>
                        <button type="button" class="hover:text-danger-600">
                            <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
