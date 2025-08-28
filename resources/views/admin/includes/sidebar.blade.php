<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div class="p-3 border-b">
        <a href="#" class="">
            <img src="{{ asset('assets/img/md-logo.svg') }}" class="light-logo" alt="Logo">
        </a>
    </div>
    <div class="sidebar-menu-area flex flex-col justify-between">
        <ul class="sidebar-menu flex flex-col gap-8" id="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ areActiveRoutes(['admin.dashboard']) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="pe-2 !text-white" width="36" height="30"
                        viewBox="0 0 30 30" fill="none">
                        <path
                            d="M10.3085 27.5342H5.39997C2.95784 27.5342 1.7793 26.4325 1.7793 24.158V5.4411C1.7793 3.16663 2.96999 2.06494 5.39997 2.06494H10.3085C12.7507 2.06494 13.9292 3.16663 13.9292 5.4411V24.158C13.9292 26.4325 12.7385 27.5342 10.3085 27.5342ZM5.39997 3.84186C3.85693 3.84186 3.60178 4.24463 3.60178 5.4411V24.158C3.60178 25.3545 3.85693 25.7573 5.39997 25.7573H10.3085C11.8516 25.7573 12.1067 25.3545 12.1067 24.158V5.4411C12.1067 4.24463 11.8516 3.84186 10.3085 3.84186H5.39997Z"
                            fill="#292D32" />
                        <path
                            d="M24.2792 16.8726H19.3707C16.9285 16.8726 15.75 15.7709 15.75 13.4965V5.4411C15.75 3.16663 16.9407 2.06494 19.3707 2.06494H24.2792C26.7214 2.06494 27.8999 3.16663 27.8999 5.4411V13.4965C27.8999 15.7709 26.7092 16.8726 24.2792 16.8726ZM19.3707 3.84186C17.8276 3.84186 17.5725 4.24463 17.5725 5.4411V13.4965C17.5725 14.6929 17.8276 15.0957 19.3707 15.0957H24.2792C25.8223 15.0957 26.0774 14.6929 26.0774 13.4965V5.4411C26.0774 4.24463 25.8223 3.84186 24.2792 3.84186H19.3707Z"
                            fill="#292D32" />
                        <path
                            d="M24.2792 27.5335H19.3707C16.9285 27.5335 15.75 26.4318 15.75 24.1574V22.0251C15.75 19.7506 16.9407 18.6489 19.3707 18.6489H24.2792C26.7214 18.6489 27.8999 19.7506 27.8999 22.0251V24.1574C27.8999 26.4318 26.7092 27.5335 24.2792 27.5335ZM19.3707 20.4258C17.8276 20.4258 17.5725 20.8286 17.5725 22.0251V24.1574C17.5725 25.3538 17.8276 25.7566 19.3707 25.7566H24.2792C25.8223 25.7566 26.0774 25.3538 26.0774 24.1574V22.0251C26.0774 20.8286 25.8223 20.4258 24.2792 20.4258H19.3707Z"
                            fill="#292D32" />
                    </svg>
                    <span class="text-lg">{{ __db('dashboard') }}</span>
                </a>
            </li>

            @canany(['manage_events', 'del_manage_events', 'delegation_manage_events', 'escort_manage_events', 'driver_manage_events', 'accommodation_manage_events'])
                <li>
                    <a href="{{ route('events.index') }}"
                        class="{{ areActiveRoutes(['events.index', 'events.create', 'events.edit']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('events') }}</span>
                    </a>
                </li>
            @endcanany

            @canany(['manage_delegations', 'del_manage_delegations', 'escort_manage_delegations', 'driver_manage_delegations', 'accommodation_manage_delegations'])
                <li>
                    <a href="{{ route('delegations.index') }}"
                        class="{{ areActiveRoutes(['delegations.index', 'delegations.create', 'delegations.edit']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('delegations') }}</span>
                    </a>
                </li>
            @endcanany


            @canany(['view_travels', 'del_view_travels', 'escort_view_travels', 'driver_view_travels', 'accommodation_view_travels'])
                <li>
                    <a href="{{ route('delegations.arrivalsIndex') }}"
                        class="{{ areActiveRoutes(['delegations.arrivalsIndex']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('arrivals') }}</span>
                    </a>
                </li>
            @endcanany

            @canany(['view_travels', 'del_view_travels', 'escort_view_travels', 'driver_view_travels', 'accommodation_view_travels'])
                <li>
                    <a href="{{ route('delegations.departuresIndex') }}"
                        class="{{ areActiveRoutes(['delegations.departuresIndex']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('departures') }}</span>
                    </a>
                </li>
            @endcanany

            @canany(['view_interviews', 'del_view_interviews', 'escort_view_interviews', 'driver_view_interviews', 'accommodation_view_interviews'])
                <li>
                    <a href="{{ route('delegations.interviewsIndex') }}"
                        class="{{ areActiveRoutes(['delegations.interviewsIndex']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('interview_requests') }}</span>
                    </a>
                </li>
            @endcanany

            @canany(['manage_escorts', 'del_manage_escorts', 'delegation_manage_escorts', 'driver_manage_escorts', 'accommodation_manage_escorts'])
                <li>
                    <a href="{{ route('escorts.index') }}"
                        class="{{ areActiveRoutes(['escorts.index', 'escorts.create', 'escorts.edit']) }}">
                        <svg class="pe-2" width="30" height="30" viewBox="0 0 29 29" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20.5249 24.7773H8.55908C3.77275 24.7773 2.57617 23.6107 2.57617 18.944V9.61068C2.57617 4.94401 3.77275 3.77734 8.55908 3.77734H20.5249C25.3112 3.77734 26.5078 4.94401 26.5078 9.61068V18.944C26.5078 23.6107 25.3112 24.7773 20.5249 24.7773Z"
                                stroke="#292D32" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M16.9355 9.61084H22.9185" stroke="#292D32" stroke-width="1.75"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M18.1328 14.2773H22.9191" stroke="#292D32" stroke-width="1.75"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M20.5254 18.9438H22.9186" stroke="#292D32" stroke-width="1.75"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M10.3553 13.4489C11.5514 13.4489 12.5211 12.5035 12.5211 11.3373C12.5211 10.171 11.5514 9.22559 10.3553 9.22559C9.15912 9.22559 8.18945 10.171 8.18945 11.3373C8.18945 12.5035 9.15912 13.4489 10.3553 13.4489Z"
                                stroke="#292D32" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M14.5421 19.3289C14.3746 17.6372 12.9985 16.3072 11.2635 16.1556C10.6652 16.0972 10.0549 16.0972 9.44465 16.1556C7.70961 16.3189 6.33354 17.6372 6.16602 19.3289"
                                stroke="#292D32" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="text-lg">{{ __db('escorts') }} </span>
                    </a>
                </li>
            @endcanany

            @canany(['manage_drivers', 'del_manage_drivers', 'delegation_manage_drivers', 'escort_manage_drivers', 'accommodation_manage_drivers'])
                <li>
                    <a href="{{ route('drivers.index') }}"
                        class="{{ areActiveRoutes(['drivers.index', 'drivers.create', 'drivers.edit']) }}">
                        <svg class="pe-2" width="30" height="30" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-car-front-icon lucide-car-front">
                            <path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8" />
                            <path d="M7 14h.01" />
                            <path d="M17 14h.01" />
                            <rect width="18" height="8" x="3" y="10" rx="2" />
                            <path d="M5 18v2" />
                            <path d="M19 18v2" />
                        </svg>
                        <span class="text-lg">{{ __db('drivers') }}</span>
                    </a>
                </li>
            @endcanany

            @canany(['manage_accommodations', 'del_manage_accommodations', 'delegation_manage_accommodations', 'escort_manage_accommodations', 'driver_manage_accommodations'])
                <li>
                    <a href="{{ route('accommodations.index') }}"
                        class="{{ areActiveRoutes(['accommodations.index', 'accommodations.show', 'accommodations.import']) }}">
                        <svg class="pe-2" width="30" height="30" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                        </svg>
                        <span class="text-lg">{{ __db('accommodations') }}</span>
                    </a>
                </li>
            @endcanany

            @canany(['manage_dropdowns', 'del_manage_dropdowns', 'delegation_manage_dropdowns', 'escort_manage_dropdowns', 'driver_manage_dropdowns', 'accommodation_manage_dropdowns'])
                <li>
                    <a href="{{ route('dropdowns.index') }}"
                        class="{{ areActiveRoutes(['dropdowns.index', 'dropdowns.options.show', 'dropdowns.bulk.import']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('dynamic_contents') }}</span>
                    </a>
                </li>
            @endcanany

            @canany(['manage_staff', 'del_manage_staff', 'delegation_manage_staff', 'escort_manage_staff', 'driver_manage_staff', 'accommodation_manage_staff'])
                <li>
                    <a href="{{ route('staffs.index') }}"
                        class="{{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('staffs') }}</span>
                    </a>
                </li>
            @endcanany

            @canany(['manage_roles', 'del_manage_roles', 'delegation_manage_roles', 'escort_manage_roles', 'driver_manage_roles', 'accommodation_manage_roles'])
                <li>
                    <a href="{{ route('roles.index') }}"
                        class="{{ areActiveRoutes(['roles.create', 'roles.edit', 'roles.index']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('roles_and_permission') }}</span>
                    </a>
                </li>
            @endcanany

            @canany(['manage_other_interview_members', 'del_manage_other_interview_members', 'delegation_manage_other_interview_members', 'escort_manage_other_interview_members', 'driver_manage_other_interview_members', 'accommodation_manage_other_interview_members'])
                <li>
                    <a href="{{ route('other-interview-members.index') }}"
                        class="{{ areActiveRoutes(['other_interview_members.create', 'other_interview_members.edit', 'other_interview_members.index']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('interview_members') }}</span>
                    </a>
                </li>
            @endcanany

            @canany(['manage_labels', 'del_manage_labels', 'delegation_manage_labels', 'escort_manage_labels', 'driver_manage_labels', 'accommodation_manage_labels'])
                <li>
                    <a href="{{ route('translations.index') }}"
                        class="{{ areActiveRoutes(['translations.create', 'translations.edit', 'translations.index']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('label_translations') }}</span>
                    </a>
                </li>
            @endcanany

            <hr>
            <li>
                <a href="#">
                    <svg class="pe-2" width="36" height="30" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span class="text-lg">{{ __db('profile') }}</span>
                </a>
            </li>
            </li>
            <li>
                <a href="{{ route('admin.logout') }}">
                    <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                    </svg>
                    <span class="text-lg">{{ __db('logout') }}</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
