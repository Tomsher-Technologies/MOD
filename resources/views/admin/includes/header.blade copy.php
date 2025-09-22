<div class="navbar-header border-b border-neutral-200 pe-0 !py-[31px]">
    <div class="flex items-center justify-between">
        <div class="col-auto me-auto">
            <img src="{{ getAdminEventLogo() }}" alt="" width="150">
        </div>

        <div class="col-auto ms-auto flex items-center gap-4">
            @php
                $events = getAllEvents();
                $currentEventId = session('current_event_id', getDefaultEventId() ?? null);
            @endphp

            @if (Auth::user()->user_type == 'admin' ||
                    Auth::user()->user_type == 'super_admin' ||
                    Auth::user()->user_type == 'staff')
                <form method="POST" action="{{ route('events.setCurrentEvent') }}" id="currentEventForm"
                    class="me-3 inline-block">
                    @csrf
                    <select name="event_id" id="current-event-select"
                        class="p-2 !pe-10 text-sm rounded border border-neutral-300 text-neutral-700 cursor-pointer"
                        onchange="document.getElementById('currentEventForm').submit();"
                        title="{{ __db('select_event') }}">
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
            @endif

            @php
                $currentRoute = Route::currentRouteName();

                $buttonConfig = [
                    'roles.index' => [
                        'text' => __db('add_new_role'),
                        'link' => route('roles.create'),
                        'permission' => ['add_role'],
                    ],
                    'staffs.index' => [
                        'text' => __db('add_new_staff'),
                        'link' => route('staffs.create'),
                        'permission' => ['add_staff'],
                    ],
                    'events.index' => [
                        'text' => __db('add_new_event'),
                        'link' => route('events.create'),
                        'permission' => ['add_event'],
                    ],
                    'other-interview-members.index' => [
                        'text' => __db('add_new_member'),
                        'link' => route('other-interview-members.create'),
                        'permission' => ['add_other_interview_members'],
                    ],
                    'delegations.index' => [
                        [
                            'text' => __db('add_new_delegation'),
                            'link' => route('delegations.create'),
                            'permission' => ['add_delegations', 'delegate_add_delegations'],
                        ],
                    ],
                    'escorts.index' => [
                        'text' => __db('add_escort'),
                        'link' => route('escorts.create'),
                        'permission' => ['add_escorts', 'escort_add_escorts'],
                    ],
                    'drivers.index' => [
                        'text' => __db('add_driver'),
                        'link' => route('drivers.create'),
                        'permission' => ['add_drivers', 'driver_add_drivers'],
                    ],
                    'accommodations.index' => [
                        'text' => __db('add_accommodation'),
                        'link' => route('accommodation-delegations'),
                        'permission' => ['view_accommodation_delegations', 'hotel_view_accommodation_delegations'],
                    ],
                    'news.index' => [
                        'text' => __db('add_news'),
                        'link' => route('news.create'),
                        'permission' => ['add_news'],
                    ],
                    'committees.index' => [
                        'text' => __db('add_committee_member'),
                        'link' => route('committees.create'),
                        'permission' => ['add_committee'],
                    ],
                ];
                $config = $buttonConfig[$currentRoute] ?? null;
            @endphp

            @if ($config)
                @if (is_array($config) && isset($config[0]) && is_array($config[0]))
                    {{-- Multiple buttons for the same route --}}
                    @foreach ($config as $button)
                        @if (can($button['permission']))
                            <a href="{{ $button['link'] }}"
                                class="btn me-8 text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">
                                <svg class="w-6 h-6 text-white me-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 12h14m-7 7V5" />
                                </svg>
                                <span>{{ $button['text'] }}</span>
                            </a>
                        @endif
                    @endforeach
                @elseif (is_array($config) && isset($config['text']))
                    {{-- Single button --}}
                    @if (can($config['permission']))
                        <a href="{{ $config['link'] }}"
                            class="btn me-8 text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">
                            <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7 7V5" />
                            </svg>
                            <span>{{ $config['text'] }}</span>
                        </a>
                    @endif
                @endif
            @endif
        </div>
        <div class="col-auto ml-4 mr-2">
            <div class="flex flex-wrap items-center gap-6">

                <button id="languageToggleBtn" data-dropdown-toggle="languageDropdown"
                    class="flex items-center justify-center h-10 w-10 rounded-full bg-neutral-200 hover:bg-neutral-300">
                    🌐
                </button>

                <div id="languageDropdown"
                    class="hidden absolute right-0 z-20 mt-2 w-36 rounded-lg bg-white shadow-lg border border-gray-200">
                    <ul class="py-1 text-sm text-gray-700" aria-labelledby="languageToggleBtn">
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


                <button data-dropdown-toggle="dropdownNotification"
                    class="has-indicator flex h-10 w-10 items-center justify-center rounded-full bg-neutral-200 "
                    type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <span
                        class="absolute top-3 -end-[8px] -translate-y-1/2 px-1 py-0.5 leading-[1] flex text-sm items-center justify-center badge rounded-full bg-danger-600 text-white">{{ auth()->user()->unreadNonAlertNotifications()->count() }}</span>
                </button>
                <div id="dropdownNotification"
                    class="z-10 hidden w-full max-w-[394px] overflow-hidden rounded-2xl bg-white shadow-lg ">
                    <div
                        class="m-4 flex items-center justify-between gap-2 rounded-lg bg-primary-50 px-4 py-2 dark:bg-primary-600/25">
                        <h6 class="mb-0 text-lg font-semibold text-neutral-900">
                            {{ __db('notifications') }}
                        </h6>
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white font-bold text-primary-600 dark:bg-neutral-600 ">{{ auth()->user()->unreadNonAlertNotifications()->count() }}</span>
                    </div>
                    <div class="scroll-sm !border-t-0">
                        <div class="max-h-[400px] overflow-y-auto">
                            @php
                                $unreadNotifications = auth()->user()->unreadNonAlertNotifications()->take(5)->get();
                            @endphp
                            @forelse($unreadNotifications as $notification)
                                @php
                                    $data = is_string($notification->data)
                                        ? json_decode($notification->data, true)
                                        : $notification->data;
                                    $message = '';
                                    if (isset($data['message'])) {
                                        if (is_array($data['message'])) {
                                            $lang = getActiveLanguage();
                                            if ($lang !== 'en' && isset($data['message']['ar'])) {
                                                $message = $data['message']['ar'];
                                            } else {
                                                $message = $data['message']['en'] ?? '';
                                            }
                                        } else {
                                            $message = $data['message'];
                                        }
                                    }
                                    $module = $data['module'] ?? null;
                                    $action = $data['action'] ?? null;
                                    $delegationId = $data['delegation_id'] ?? null;
                                    $submoduleId = $data['submodule_id'] ?? null;
                                    
                                    $moduleName = null;
                                    $moduleCode = null;
                                    $moduleDetails = [];
                                    
                                    if (isset($data['changes'])) {
                                        if (isset($data['changes']['escort_name'])) {
                                            $moduleName = $data['changes']['escort_name'];
                                        } elseif (isset($data['changes']['driver_name'])) {
                                            $moduleName = $data['changes']['driver_name'];
                                        } elseif (isset($data['changes']['member_name'])) {
                                            $moduleName = $data['changes']['member_name'];
                                        } elseif (isset($data['changes']['delegation_code'])) {
                                            $moduleCode = $data['changes']['delegation_code'];
                                        } elseif (isset($data['changes']['code'])) {
                                            $moduleCode = $data['changes']['code'];
                                        }
                                        
                                        foreach ($data['changes'] as $key => $value) {
                                            if (in_array($key, ['escort_name', 'driver_name', 'member_name', 'delegation_code', 'code', 'title'])) {
                                                continue;
                                            }
                                            
                                            $displayValue = is_array($value) ? (isset($value['new']) ? $value['new'] : json_encode($value)) : $value;
                                            if (!empty($displayValue) && $displayValue !== 'N/A') {
                                                $moduleDetails[$key] = $displayValue;
                                            }
                                        }
                                    }
                                    
                                    $url = '#';

                                    if ($delegationId) {
                                        $url = route('delegations.show', $delegationId);
                                    } 
                                    elseif ($module && $submoduleId) {
                                        switch (strtolower($module)) {
                                            case 'escorts':
                                                $url = route('escorts.edit', $submoduleId);
                                                break;
                                            case 'drivers':
                                                $url = route('drivers.edit', $submoduleId);
                                                break;
                                            default:
                                                switch (strtolower($module)) {
                                                    case 'escorts':
                                                        $escortName = null;
                                                        if (isset($data['changes']['escort_name'])) {
                                                            $escortName = $data['changes']['escort_name'];
                                                        } elseif (isset($data['changes']['member_name'])) {
                                                            $escortName = $data['changes']['member_name'];
                                                        }
                                                        
                                                        if ($escortName) {
                                                            $url = route('escorts.index', ['search' => $escortName]);
                                                        } else {
                                                            $url = route('escorts.index');
                                                        }
                                                        break;
                                                    case 'drivers':
                                                        $driverName = null;
                                                        if (isset($data['changes']['driver_name'])) {
                                                            $driverName = $data['changes']['driver_name'];
                                                        } elseif (isset($data['changes']['member_name'])) {
                                                            $driverName = $data['changes']['member_name'];
                                                        }
                                                        
                                                        if ($driverName) {
                                                            $url = route('drivers.index', ['search' => $driverName]);
                                                        } else {
                                                            $url = route('drivers.index');
                                                        }
                                                        break;
                                                    default:
                                                        $url = '#';
                                                }
                                        }
                                    }
                                    elseif ($module) {
                                        switch (strtolower($module)) {
                                            case 'escorts':
                                                $escortName = null;
                                                if (isset($data['changes']['escort_name'])) {
                                                    $escortName = $data['changes']['escort_name'];
                                                } elseif (isset($data['changes']['member_name'])) {
                                                    $escortName = $data['changes']['member_name'];
                                                }
                                                
                                                if ($escortName) {
                                                    $url = route('escorts.index', ['search' => $escortName]);
                                                } else {
                                                    $url = route('escorts.index');
                                                }
                                                break;
                                            case 'drivers':
                                                $driverName = null;
                                                if (isset($data['changes']['driver_name'])) {
                                                    $driverName = $data['changes']['driver_name'];
                                                } elseif (isset($data['changes']['member_name'])) {
                                                    $driverName = $data['changes']['member_name'];
                                                }
                                                
                                                if ($driverName) {
                                                    $url = route('drivers.index', ['search' => $driverName]);
                                                } else {
                                                    $url = route('drivers.index');
                                                }
                                                break;
                                            default:
                                                $url = '#';
                                        }
                                    }
                                @endphp
                                <a href="{{ $url }}"
                                    class="flex justify-between gap-1 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <h6 class="fw-semibold mb-1 text-sm">{{ $module ?? __db('notification') }}
                                            </h6>
                                            <p class="mb-0 line-clamp-1 text-sm">
                                                {{ $message }}
                                            </p>
                                            @if($moduleName || $moduleCode || !empty($moduleDetails))
                                            <div class="text-xs text-neutral-500 mt-1">
                                                @if($moduleName)
                                                <div><span class="font-medium">{{ __db('name') }}:</span> {{ $moduleName }}</div>
                                                @endif
                                                @if($moduleCode)
                                                <div><span class="font-medium">{{ __db('code') }}:</span> {{ $moduleCode }}</div>
                                                @endif
                                                @if(!empty($moduleDetails))
                                                <div class="mt-1">
                                                    @foreach(array_slice($moduleDetails, 0, 2) as $key => $value)
                                                    <div>
                                                        <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> 
                                                        {{ is_array($value) ? json_encode($value) : $value }}
                                                    </div>
                                                    @endforeach
                                                    @if(count($moduleDetails) > 2)
                                                    <div>... {{ count($moduleDetails) - 2 }} {{ __db('more_details') }}</div>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <span
                                            class="text-sm text-neutral-500">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-2 text-center text-neutral-500">
                                    {{ __db('no_notifications') }}
                                </div>
                            @endforelse
                        </div>
                        <div class="px-4 py-2 text-center">
                            <a href="{{ route('notifications.index') }}"
                                class="text-center font-semibold text-primary-600 hover:underline dark:text-primary-600">
                                {{ __db('see_all_notifications') }}
                            </a>
                        </div>
                    </div>
                </div>

                <button id="alertDropdownBtn" data-dropdown-toggle="dropdownAlert"
                    class="has-indicator flex h-10 w-10 items-center justify-center rounded-full bg-neutral-200 "
                    type="button" onclick="showLatestAlertModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span
                        class="absolute top-3 -end-[8px] -translate-y-1/2 px-1 py-0.5 leading-[1] flex text-sm items-center justify-center badge rounded-full bg-danger-600 text-white">{{ auth()->user()->unreadNotifications()->whereNotNull('alert_id')->count() }}</span>
                </button>

                <div id="dropdownAlert"
                    class="z-10 hidden w-full max-w-[394px] overflow-hidden rounded-2xl bg-white shadow-lg ">
                    <div
                        class="m-4 flex items-center justify-between gap-2 rounded-lg bg-primary-50 px-4 py-2 dark:bg-primary-600/25">
                        <h6 class="mb-0 text-lg font-semibold text-neutral-900">
                            {{ __db('alerts') }}
                        </h6>
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white font-bold text-primary-600 dark:bg-neutral-600 ">{{ auth()->user()->unreadNotifications()->whereNotNull('alert_id')->count() }}</span>
                    </div>

                    <div class="scroll-sm !border-t-0">
                        <div class="max-h-[400px] overflow-y-auto">
                            @php
                                $unreadAlerts = auth()
                                    ->user()
                                    ->unreadNotifications()
                                    ->whereNotNull('alert_id')
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($unreadAlerts as $notification)
                                @php
                                    $data = is_string($notification->data)
                                        ? json_decode($notification->data, true)
                                        : $notification->data;
                                    $message = '';
                                    if (isset($data['message'])) {
                                        if (is_array($data['message'])) {
                                            $lang = getActiveLanguage();
                                            if ($lang !== 'en' && isset($data['message']['ar'])) {
                                                $message = $data['message']['ar'];
                                            } else {
                                                $message = $data['message']['en'] ?? '';
                                            }
                                        } else {
                                            $message = $data['message'];
                                        }
                                    }
                                    $title = '';
                                    if (isset($data['title'])) {
                                        if (is_array($data['title'])) {
                                            $lang = getActiveLanguage();
                                            if ($lang !== 'en' && isset($data['title']['ar'])) {
                                                $title = $data['title']['ar'];
                                            } else {
                                                $title = $data['title']['en'] ?? '';
                                            }
                                        } else {
                                            $title = $data['title'];
                                        }
                                    } else {
                                        if (isset($data['changes']['title'])) {
                                            if (is_array($data['changes']['title'])) {
                                                $lang = getActiveLanguage();
                                                if ($lang !== 'en' && isset($data['changes']['title']['ar'])) {
                                                    $title = $data['changes']['title']['ar'];
                                                } else {
                                                    $title = $data['changes']['title']['en'] ?? '';
                                                }
                                            } else {
                                                $title = $data['changes']['title'];
                                            }
                                        }
                                    }
                                @endphp
                                <a href="#"
                                    onclick="showAlertModal({{ $data['alert_id'] ?? 0 }}, '{{ addslashes($title) }}', '{{ addslashes($message) }}'); return false;"
                                    class="flex justify-between gap-1 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <h6 class="fw-semibold mb-1 text-sm">{{ $title ?: __db('alert') }}</h6>
                                            <p class="mb-0 line-clamp-1 text-sm">
                                                {{ $message }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <span
                                            class="text-sm text-neutral-500">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-2 text-center text-neutral-500">
                                    {{ __db('no_alerts') }}
                                </div>
                            @endforelse
                        </div>
                        <div class="px-4 py-2 text-center">
                            <a href="{{ route('alerts.index') }}"
                                class="text-center font-semibold text-primary-600 hover:underline dark:text-primary-600">
                                {{ __db('see_all_alerts') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div id="alertModal" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow ">
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                                <h3 class="text-xl font-semibold text-gray-900 " id="alertModalTitle">
                                    {{ __db('alert') }}
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    onclick="closeAlertModal()">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">{{ __db('close') }}</span>
                                </button>
                            </div>
                            <div class="p-4 md:p-5 space-y-4">
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400"
                                    id="alertModalMessage">
                                    {{ __db('alert_message') }}
                                </p>
                            </div>
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b ">
                                <button onclick="closeAlertModal()" type="button"
                                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400  dark:hover:text-white dark:hover:bg-gray-700">
                                    {{ __db('close') }}
                                </button>
                                <a href="{{ route('alerts.index') }}" id="viewAllAlertsBtn" type="button"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    {{ __db('view_all_alerts') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>



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

@section('script')
    <script>
        function showLatestAlertModal() {
            fetch('/mod-admin/alerts/latest', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.alert) {
                        document.getElementById('alertModalTitle').textContent = data.alert.title;
                        document.getElementById('alertModalMessage').textContent = data.alert.message;

                        document.getElementById('viewAllAlertsBtn').href = '/mod-admin/alerts/' + data.alert.id;

                        document.getElementById('alertModal').classList.remove('hidden');
                        document.getElementById('alertModal').classList.add('flex');

                        if (data.alert.id > 0) {
                            fetch('/mod-admin/alerts/' + data.alert.id + '/mark-as-read', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const alertCountElement = document.querySelector('#dropdownAlert .badge');
                                    if (alertCountElement) {
                                        let count = parseInt(alertCountElement.textContent) || 0;
                                        if (count > 0) {
                                            alertCountElement.textContent = count - 1;
                                        }
                                    }
                                });
                        }
                    } else {
                        document.getElementById('dropdownAlert').classList.toggle('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching latest alert:', error);
                    document.getElementById('dropdownAlert').classList.toggle('hidden');
                });
        }

        function showAlertModal(alertId, title, message) {
            document.getElementById('alertModalTitle').textContent = title;
            document.getElementById('alertModalMessage').textContent = message;

            document.getElementById('viewAllAlertsBtn').href = '/mod-admin/alerts/' + alertId;

            document.getElementById('alertModal').classList.remove('hidden');
            document.getElementById('alertModal').classList.add('flex');

            if (alertId > 0) {
                fetch('/mod-admin/alerts/' + alertId + '/mark-as-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const alertCountElement = document.querySelector('#dropdownAlert .badge');
                        if (alertCountElement) {
                            let count = parseInt(alertCountElement.textContent) || 0;
                            if (count > 0) {
                                alertCountElement.textContent = count - 1;
                            }
                        }
                    });
            }
        }

        function closeAlertModal() {
            document.getElementById('alertModal').classList.add('hidden');
            document.getElementById('alertModal').classList.remove('flex');
        }

        document.getElementById('alertModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeAlertModal();
            }
        });
    </script>
@endsection
