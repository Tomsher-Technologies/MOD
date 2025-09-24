<div
    class="navbar-header sticky top-0 z-40 bg-white border-b border-neutral-200 px-4 py-4 flex items-center justify-between">
    <!-- Left: Logo and Sidebar Toggle -->
    <div class="flex items-center space-x-4">
        <button id="sidebarToggleBtn" class="md:hidden p-2 rounded-md hover:bg-gray-200" aria-expanded="false"
            aria-label="Toggle sidebar menu">
            <!-- Always hamburger menu icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="flex items-center">
            <img src="{{ getAdminEventLogo() }}" alt="Event Logo" width="150" class="object-contain">
        </div>
    </div>

    <!-- Right: Navbar Items -->
    <!-- Right: Navbar Items -->
    <div class="flex items-center space-x-4">

        <div class="col-auto ms-auto flex items-center gap-4">
            @php
                $events = getAllEvents();
                $currentEventId = session('current_event_id', getDefaultEventId() ?? null);
            @endphp

            @php
                $currentRoute = Route::currentRouteName();
                $isIndexRoute = str_ends_with($currentRoute, '.index');
            @endphp

            @if ((Auth::user()->user_type == 'admin' ||
                    Auth::user()->user_type == 'super_admin' ||
                    Auth::user()->user_type == 'staff') && $isIndexRoute)
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
                                class="btn me-8 text-md  !bg-[#B68A35] text-white rounded-lg h-12">
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
                            class="btn me-8 text-md !bg-[#B68A35] text-white rounded-lg h-12">
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
            <div class="flex flex-wrap items-center gap-3">

                <a href="{{ route('clear.cache') }}"  class="flex items-center justify-center h-10 w-10 rounded-full bg-neutral-200 hover:bg-neutral-300" title="{{ __db('clear_cache') }}" onclick="return confirm(__db('are_you_sure_clear_cache'))">
                    <svg class="size-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="rgba(7,98,181,1)"><path d="M12 4C9.25144 4 6.82508 5.38626 5.38443 7.5H8V9.5H2V3.5H4V5.99936C5.82381 3.57166 8.72764 2 12 2C17.5228 2 22 6.47715 22 12H20C20 7.58172 16.4183 4 12 4ZM4 12C4 16.4183 7.58172 20 12 20C14.7486 20 17.1749 18.6137 18.6156 16.5H16V14.5H22V20.5H20V18.0006C18.1762 20.4283 15.2724 22 12 22C6.47715 22 2 17.5228 2 12H4Z"></path></svg>
                </a>

                <button id="languageToggleBtn" data-dropdown-toggle="languageDropdown" title="{{ __db('change_language') }}"
                    class="flex items-center justify-center h-10 w-10 rounded-full bg-neutral-200 hover:bg-neutral-300">
                    <svg class="size-7 stroke-current transition-colors duration-200 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="36" height="30" fill="none" viewBox="0 0 24 24" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m13 19 3.5-9 3.5 9m-6.125-2h5.25M3 7h7m0 0h2m-2 0c0 1.63-.793 3.926-2.239 5.655M7.5 6.818V5m.261 7.655C6.79 13.82 5.521 14.725 4 15m3.761-2.345L5 10m2.761 2.655L10.2 15" />
                    </svg>
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
                        class="absolute top-3 -end-[8px] -translate-y-1/2 px-1 py-0.5 leading-[1] flex text-sm items-center justify-center badge rounded-full bg-danger-600 text-white">{{ auth()->user()->unreadNotifications()->where('event_id', $currentEventId)->count() }}</span>
                </button>
                <div id="dropdownNotification"
                    class="z-10 hidden w-full max-w-[394px] overflow-hidden rounded-2xl bg-white shadow-lg">
                    <div
                        class="m-4 flex items-center justify-between gap-2 rounded-lg bg-primary-50 px-4 py-2 dark:bg-primary-600/25">
                        <h6 class="mb-0 text-lg font-semibold text-neutral-900">
                            {{ __db('notifications') }}
                        </h6>
                        <span id="notification-count-display"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white font-bold text-primary-600">0</span>
                    </div>
                    <div class="scroll-sm !border-t-0">
                        <div id="unread-notifications-list" class="max-h-[400px] overflow-y-auto">
                            <!-- Initial loading state -->
                            <div class="px-4 py-2 text-center text-neutral-500">
                                {{ __db('loading') }}
                            </div>
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
                    type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                                    <span id="alert-count-badge"
                    class="absolute top-3 -end-[8px] -translate-y-1/2 px-1 py-0.5 leading-[1] flex text-sm items-center justify-center badge rounded-full bg-danger-600 text-white">0</span>
                </button>

                <div id="dropdownAlert"
                    class="z-10 hidden w-full max-w-[394px] overflow-hidden rounded-2xl bg-white shadow-lg">
                    <div
                        class="m-4 flex items-center justify-between gap-2 rounded-lg bg-primary-50 px-4 py-2 dark:bg-primary-600/25">
                        <h6 class="mb-0 text-lg font-semibold text-neutral-900">
                            {{ __db('alerts') }}
                        </h6>
                        <span id="alert-count-display"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white font-bold text-primary-600">0</span>
                    </div>
                    <div class="scroll-sm !border-t-0">
                        <div id="unread-alerts-list" class="max-h-[400px] overflow-y-auto">
                            <!-- Initial loading state -->
                            <div class="px-4 py-2 text-center text-neutral-500">
                                {{ __db('loading') }}
                            </div>
                        </div>
                        <div class="px-4 py-2 text-center">
                            <a href="{{ route('alerts.index') }}"
                                class="text-center font-semibold text-primary-600 hover:underline dark:text-primary-600">
                                {{ __db('see_all_alerts') }}
                            </a>
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


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const closeBtn = document.getElementById('sidebarCloseBtn');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', () => {
                if (sidebar.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    closeSidebar();
                }
            });
        });
    </script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const transStrings = {
            name: '{{ __db('name') }}',
            code: '{{ __db('code') }}',
            more_details: '{{ __db('more_details') }}',
            no_notifications: '{{ __db('no_notifications') }}',
            no_alerts: '{{ __db('no_alerts') }}',
            loading: '{{ __db('loading') }}'
        };

        let notificationsCache = {
            data: null,
            timestamp: 0,
            isLoading: false
        };

        let alertsCache = {
            count: 0,
            timestamp: 0,
            isLoading: false
        };

        const CACHE_DURATION = 0; 

        function translateFieldName(fieldName) {
            const translations = {
                'escort_name': '{{ __db('escort_name') }}',
                'driver_name': '{{ __db('driver_name') }}',
                'member_name': '{{ __db('member_name') }}',
                'delegation_code': '{{ __db('delegation_code') }}',
                'title': '{{ __db('title') }}',
                'status': '{{ __db('status') }}',
                'created_at': '{{ __db('created_at') }}',
                'updated_at': '{{ __db('updated_at') }}'
            };
            return translations[fieldName] || fieldName.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        function updateNotificationBadge(unreadCount) {
            const notificationBadge = document.querySelector('[data-dropdown-toggle="dropdownNotification"] .badge');
            const notificationCount = document.getElementById('notification-count-display');

            if (notificationBadge) {
                notificationBadge.textContent = unreadCount;
                notificationBadge.style.display = 'flex';
            }

            if (notificationCount) {
                notificationCount.textContent = unreadCount;
            }
        }

        async function fetchUnreadCount() {
            console.log("Fetching unread count");

            try {
                const response = await fetch('/mod-events/notifications/count', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    updateNotificationBadge(data.unread_count);
                }

                return data;
            } catch (error) {
                console.error('Error fetching unread count:', error);
                return null;
            }
        }

        async function fetchNotifications() {
            console.log("Fetching notifications for dropdown");

            const now = Date.now();
            // if (notificationsCache.data && (now - notificationsCache.timestamp) < CACHE_DURATION) {
            //     console.log("Using cached notifications");
            //     updateNotificationDropdown(notificationsCache.data.notifications, notificationsCache.data.unread_count);
            //     return notificationsCache.data;
            // }

            // if (notificationsCache.isLoading) {
            //     console.log("Already loading notifications");
            //     return;
            // }

            notificationsCache.isLoading = true;

            try {
                const response = await fetch('/mod-events/notifications/fetch', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    notificationsCache.data = data;
                    notificationsCache.timestamp = now;
                    updateNotificationDropdown(data.notifications, data.unread_count);
                }

                return data;
            } catch (error) {
                console.error('Error fetching notifications:', error);
                return null;
            } finally {
                notificationsCache.isLoading = false;
            }
        }

        function updateNotificationDropdown(notifications, totalUnreadCount) {
            const notificationList = document.getElementById('unread-notifications-list');
            const notificationCount = document.getElementById('notification-count-display');

            if (!notificationList) return;

            if (notificationCount) {
                notificationCount.textContent = totalUnreadCount;
            }

            notificationList.innerHTML = '';

            if (notifications.length === 0) {
                const noNotificationItem = document.createElement('div');
                noNotificationItem.className = 'px-4 py-2 text-center text-neutral-500';
                noNotificationItem.textContent = transStrings.no_notifications;
                notificationList.appendChild(noNotificationItem);
                return;
            }

            notifications.forEach(notification => {
                const notificationItem = document.createElement('a');
                notificationItem.href = notification.url || '#';
                notificationItem.className = 'flex justify-between gap-1 px-4 py-2 hover:bg-gray-100 block';

                let contentHTML = `
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <h6 class="fw-semibold mb-1 text-sm">${notification.module || 'Notification'}</h6>
                        <p class="mb-0 line-clamp-1 text-sm">
                            ${notification.message || ''}
                        </p>`;

                if (notification.module_name || notification.module_code || (notification.module_details && Object
                        .keys(notification.module_details).length > 0)) {
                    contentHTML += `<div class="text-xs text-neutral-500 mt-1">`;

                    if (notification.module_name) {
                        contentHTML +=
                            `<div><span class="font-medium">${transStrings.name}:</span> ${notification.module_name}</div>`;
                    }

                    if (notification.module_code) {
                        contentHTML +=
                            `<div><span class="font-medium">${transStrings.code}:</span> ${notification.module_code}</div>`;
                    }

                    if (notification.module_details && Object.keys(notification.module_details).length > 0) {
                        contentHTML += '<div class="mt-1">';
                        let detailCount = 0;

                        for (const [key, value] of Object.entries(notification.module_details)) {
                            if (detailCount >= 2) break;
                            let fieldName = translateFieldName(key);
                            let displayValue = typeof value === 'object' ? JSON.stringify(value) : value;
                            contentHTML +=
                                `<div><span class="font-medium">${fieldName}:</span> ${displayValue}</div>`;
                            detailCount++;
                        }

                        if (Object.keys(notification.module_details).length > 2) {
                            contentHTML +=
                                `<div>... ${Object.keys(notification.module_details).length - 2} ${transStrings.more_details}</div>`;
                        }

                        contentHTML += '</div>';
                    }

                    contentHTML += `</div>`;
                }

                contentHTML += `
                    </div>
                </div>
                <div class="shrink-0">
                    <span class="text-sm text-neutral-500">${notification.created_at || ''}</span>
                </div>`;

                notificationItem.innerHTML = contentHTML;
                notificationList.appendChild(notificationItem);
            });
        }

        function updateAlertBadge(alertCount) {
            const alertBadge = document.getElementById('alert-count-badge');

            if (alertBadge) {
                alertBadge.textContent = alertCount;
                alertBadge.style.display = 'flex';
            }

            alertsCache.count = alertCount;
            alertsCache.timestamp = Date.now();
        }

        async function fetchAlertCount() {
            console.log("Fetching alert count");

            try {
                const response = await fetch('/mod-events/alerts/count', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    updateAlertBadge(data.unread_count);
                    return data.unread_count;
                }

                return 0;
            } catch (error) {
                console.error('Error fetching alert count:', error);
                return 0;
            }
        }

        async function showLatestAlertModal() {
            console.log("fetching alerts for dropdown");

            try {
                const currentEventSelect = document.getElementById('current-event-select');
                const currentEventId = currentEventSelect ? currentEventSelect.value : null;

                const response = await fetch('/mod-events/alerts/latest', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const alertNotifications = data.alerts;
                    updateAlertDropdown(alertNotifications, data.unread_count);

                    console.log("alertNotifications",alertNotifications);
                    
                } else {
                    updateAlertDropdown([], 0);
                }

                return data;
            } catch (error) {
                console.error('Error fetching alerts for dropdown:', error);
                updateAlertDropdown([], 0);
                return null;
            }
        }

        function updateAlertDropdown(alertNotifications, totalUnreadCount) {
            const alertListContainer = document.getElementById('unread-alerts-list');
            console.log("alertNotifications",alertNotifications);

            if (!alertListContainer) return;

            alertListContainer.innerHTML = '';

            const alertCountDisplay = document.getElementById('alert-count-display');
            if (alertCountDisplay) {
                alertCountDisplay.textContent = totalUnreadCount;
            }

            if (alertNotifications.length === 0) {
                const noAlertItem = document.createElement('div');
                noAlertItem.className = 'px-4 py-2 text-center text-neutral-500';
                noAlertItem.textContent = transStrings.no_alerts || 'No alerts';
                alertListContainer.appendChild(noAlertItem);
            } else {
                alertNotifications.forEach(alert => {
                    const alertItem = document.createElement('a');
                    alertItem.href = `/mod-events/alerts/${alert.id}`;
                    alertItem.className = 'flex justify-between gap-1 px-4 py-2 hover:bg-gray-100';

                    alertItem.addEventListener('click', function(e) {
                        markAlertAsRead(alert.id);
                    });

                    const title = alert.title || 'Alert';
                    const message = alert.message || 'No message';
                    const createdAt = alert.created_at || '';

                    let contentHTML = `
                        <div class="flex items-center gap-3">
                            <div>
                                <h6 class="fw-semibold mb-1 text-sm">${title}</h6>
                                <p class="mb-0 line-clamp-1 text-sm">
                                    ${message}
                                </p>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <span class="text-sm text-neutral-500">${createdAt}</span>
                        </div>`;

                    alertItem.innerHTML = contentHTML;
                    alertListContainer.appendChild(alertItem);
                });
            }
        }

        async function markAlertAsRead(alertId) {
            try {
                const response = await fetch('/mod-events/alerts/' + alertId + '/mark-as-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    fetchAlertCount();
                    fetchUnreadCount();
                }

                return data;
            } catch (error) {
                console.error('Error marking alert as read:', error);
                return null;
            }
        }

        async function markNotificationAsReadByAlertId(alertId) {
            try {
                const response = await fetch(`/mod-events/notifications/mark-by-alert/${alertId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    fetchUnreadCount();
                }

                return data;
            } catch (error) {
                console.error('Error marking notification as read by alert ID:', error);
                return null;
            }
        }
        
        async function markNotificationAsRead(notificationId) {
            try {
                const response = await fetch(`/mod-events/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    fetchUnreadCount();
                }

                return data;
            } catch (error) {
                console.error('Error marking notification as read:', error);
                return null;
            }
        }
        
        function closeAlertModal() {
            const alertModal = document.getElementById('alertModal');
            if (alertModal) {
                alertModal.classList.add('hidden');
                alertModal.classList.remove('flex');
            }
        }

        function initializeDropdownListeners() {
            const notificationDropdownToggle = document.querySelector('[data-dropdown-toggle="dropdownNotification"]');
            const notificationDropdown = document.getElementById('dropdownNotification');

            if (notificationDropdownToggle) {
                notificationDropdownToggle.addEventListener('click', function(e) {
                    setTimeout(() => {
                        const isVisible = notificationDropdown && !notificationDropdown.classList.contains(
                            'hidden');
                        if (isVisible) {
                            fetchNotifications();
                        }
                    }, 100);
                });
            }

            const alertDropdownBtn = document.getElementById('alertDropdownBtn');
            if (alertDropdownBtn) {
                alertDropdownBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    showLatestAlertModal();
                });
            }

            if (notificationDropdown) {
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            const isDropdownVisible = !notificationDropdown.classList.contains('hidden');
                            if (isDropdownVisible && !notificationsCache.isLoading) {
                                const now = Date.now();
                                if (!notificationsCache.timestamp || (now - notificationsCache.timestamp) >
                                    CACHE_DURATION) {
                                    fetchNotifications();
                                }
                            }
                        }
                    });
                });

                observer.observe(notificationDropdown, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log("Initializing notification and alert system");

            fetchUnreadCount();
            fetchAlertCount();

            initializeDropdownListeners();

            setInterval(() => {
                fetchUnreadCount();
                fetchAlertCount();
            }, 10000); 
        });
    </script>
@endpush
