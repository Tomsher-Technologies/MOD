<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div class="p-3 border-b min-h-[105px]">
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
                    <span class="text-md">{{ __db('dashboard') }}</span>
                </a>
            </li>

            @canany(['manage_events'])
            <li>
                <a href="{{ route('events.index') }}"
                    class="{{ areActiveRoutes(['events.index', 'events.create', 'events.edit']) }}">
             

                    <svg class="pe-2" width="40" height="30" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/>
</svg>


                    <span class="text-md">{{ __db('events') }}</span>
                </a>
            </li>
            @endcanany

            @canany(['manage_delegations', 'delegate_manage_delegations', 'escort_manage_delegations',
            'driver_manage_delegation', 'hotel_manage_delegations'])
            <li>
                <a href="{{ route('delegations.index') }}"
                    class="{{ areActiveRoutes(['delegations.index', 'delegations.create', 'delegations.edit']) }}">
                    <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    <span class="text-md">{{ __db('delegations') }}</span>
                </a>
            </li>
            @endcanany


            @canany(['manage_delegations', 'delegate_manage_delegations', 'escort_manage_delegations',
            'driver_manage_delegations',
            'hotel_manage_delegations'])
            <li>
                <a href="{{ route('delegations.arrivalsIndex') }}"
                    class="{{ areActiveRoutes(['delegations.arrivalsIndex']) }}">
                    <svg class="pe-2" xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plane-arrival">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M15.157 11.81l4.83 1.295a2 2 0 1 1 -1.036 3.863l-14.489 -3.882l-1.345 -6.572l2.898 .776l1.414 2.45l2.898 .776l-.12 -7.279l2.898 .777l2.052 7.797z" />
                        <path d="M3 21h18" />
                    </svg>
                    <span class="text-md">{{ __db('arrivals') }}</span>
                </a>
            </li>
            @endcanany

            @canany(['manage_delegations', 'delegate_manage_delegations', 'escort_manage_delegations',
            'driver_manage_delegations',
            'hotel_manage_delegations'])
            <li>
                <a href="{{ route('delegations.departuresIndex') }}"
                    class="{{ areActiveRoutes(['delegations.departuresIndex']) }}">
                    <svg class="pe-2" xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-plane-departure">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M14.639 10.258l4.83 -1.294a2 2 0 1 1 1.035 3.863l-14.489 3.883l-4.45 -5.02l2.897 -.776l2.45 1.414l2.897 -.776l-3.743 -6.244l2.898 -.777l5.675 5.727z" />
                        <path d="M3 21h18" />
                    </svg>
                    <span class="text-md">{{ __db('departures') }}</span>
                </a>
            </li>
            @endcanany

            @canany(['manage_delegations', 'delegate_manage_delegations', 'escort_manage_delegations',
            'driver_manage_delegations',
            'hotel_manage_delegations'])
            <li>
                <a href="{{ route('delegations.interviewsIndex') }}"
                    class="{{ areActiveRoutes(['delegations.interviewsIndex']) }}">
                    <svg class="pe-2" width="30" height="30" viewBox="0 0 30 29" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M22.6641 26.1242C21.5735 26.4383 20.2846 26.5833 18.7727 26.5833H11.3368C9.8248 26.5833 8.53591 26.4383 7.44531 26.1242C7.71796 22.9825 11.0269 20.5054 15.0547 20.5054C19.0825 20.5054 22.3915 22.9825 22.6641 26.1242Z"
                            stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M18.7732 2.41675H11.3373C5.14074 2.41675 2.66211 4.83341 2.66211 10.8751V18.1251C2.66211 22.6926 4.07493 25.1938 7.44587 26.1242C7.71852 22.9826 11.0275 20.5055 15.0553 20.5055C19.083 20.5055 22.392 22.9826 22.6647 26.1242C26.0356 25.1938 27.4484 22.6926 27.4484 18.1251V10.8751C27.4484 4.83341 24.9698 2.41675 18.7732 2.41675ZM15.0553 17.1221C12.6014 17.1221 10.6185 15.1768 10.6185 12.7843C10.6185 10.3918 12.6014 8.45841 15.0553 8.45841C17.5091 8.45841 19.492 10.3918 19.492 12.7843C19.492 15.1768 17.5091 17.1221 15.0553 17.1221Z"
                            stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M19.4927 12.7841C19.4927 15.1766 17.5097 17.122 15.0559 17.122C12.602 17.122 10.6191 15.1766 10.6191 12.7841C10.6191 10.3916 12.602 8.45825 15.0559 8.45825C17.5097 8.45825 19.4927 10.3916 19.4927 12.7841Z"
                            stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-md">{{ __db('interview_requests') }}</span>
                </a>
            </li>
            @endcanany

            @canany(['manage_escorts', 'delegate_manage_escorts', 'escort_manage_escorts', 'driver_manage_escorts',
            'hotel_manage_escorts'])
            <li>
                <a href="{{ route('escorts.index') }}"
                    class="{{ areActiveRoutes(['escorts.index', 'escorts.create', 'escorts.edit']) }}">
                    <svg class="pe-2" width="30" height="30" viewBox="0 0 29 29" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20.5249 24.7773H8.55908C3.77275 24.7773 2.57617 23.6107 2.57617 18.944V9.61068C2.57617 4.94401 3.77275 3.77734 8.55908 3.77734H20.5249C25.3112 3.77734 26.5078 4.94401 26.5078 9.61068V18.944C26.5078 23.6107 25.3112 24.7773 20.5249 24.7773Z"
                            stroke="#292D32" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16.9355 9.61084H22.9185" stroke="#292D32" stroke-width="1.75" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M18.1328 14.2773H22.9191" stroke="#292D32" stroke-width="1.75" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M20.5254 18.9438H22.9186" stroke="#292D32" stroke-width="1.75" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M10.3553 13.4489C11.5514 13.4489 12.5211 12.5035 12.5211 11.3373C12.5211 10.171 11.5514 9.22559 10.3553 9.22559C9.15912 9.22559 8.18945 10.171 8.18945 11.3373C8.18945 12.5035 9.15912 13.4489 10.3553 13.4489Z"
                            stroke="#292D32" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M14.5421 19.3289C14.3746 17.6372 12.9985 16.3072 11.2635 16.1556C10.6652 16.0972 10.0549 16.0972 9.44465 16.1556C7.70961 16.3189 6.33354 17.6372 6.16602 19.3289"
                            stroke="#292D32" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-md">{{ __db('escorts') }} </span>
                </a>
            </li>
            @endcanany

            @canany(['manage_drivers', 'delegate_manage_drivers', 'escort_manage_drivers', 'driver_manage_drivers',
            'hotel_manage_drivers'])
            <li>
                <a href="{{ route('drivers.index') }}"
                    class="{{ areActiveRoutes(['drivers.index', 'drivers.create', 'drivers.edit']) }}">
                    <svg class="pe-2" width="30" height="30" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-car-front-icon lucide-car-front">
                        <path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8" />
                        <path d="M7 14h.01" />
                        <path d="M17 14h.01" />
                        <rect width="18" height="8" x="3" y="10" rx="2" />
                        <path d="M5 18v2" />
                        <path d="M19 18v2" />
                    </svg>
                    <span class="text-md">{{ __db('drivers') }}</span>
                </a>
            </li>
            @endcanany

            @canany(['manage_accommodations', 'delegate_manage_accommodations', 'escort_manage_accommodations',
            'driver_manage_accommodations', 'hotel_manage_accommodations'])
            <li>
                <a href="{{ route('accommodations.index') }}"
                    class="{{ areActiveRoutes(['accommodations.index', 'accommodations.show', 'accommodations.import']) }}">
                    <svg class="pe-2" width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                    </svg>
                    <span class="text-md">{{ __db('accommodations') }}</span>
                </a>
            </li>
            @endcanany

            @canany(['manage_dropdowns'])
            <li>
                <a href="{{ route('dropdowns.index') }}"
                    class="{{ areActiveRoutes(['dropdowns.index', 'dropdowns.options.show', 'dropdowns.bulk.import']) }}">
                    <svg class="pe-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="36" height="30" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M19 6c0 1.657-3.134 3-7 3S5 7.657 5 6m14 0c0-1.657-3.134-3-7-3S5 4.343 5 6m14 0v6M5 6v6m0 0c0 1.657 3.134 3 7 3s7-1.343 7-3M5 12v6c0 1.657 3.134 3 7 3s7-1.343 7-3v-6"/>
</svg>


                    <span class="text-md">{{ __db('dynamic_contents') }}</span>
                </a>
            </li>
            @endcanany

            @canany(['manage_staff'])
            <li>
                <a href="{{ route('staffs.index') }}"
                    class="{{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit']) }}">

                    <svg class="pe-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="40" height="30" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-width="1.6" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
</svg>

                    <span class="text-md">{{ __db('staffs') }}</span>
                </a>
            </li>
            @endcanany

            @canany(['manage_roles'])
            <li>
                <a href="{{ route('roles.index') }}"
                    class="{{ areActiveRoutes(['roles.create', 'roles.edit', 'roles.index']) }}">
                    <svg class="pe-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="36" height="30" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
</svg>
                    <span class="text-md">{{ __db('roles_and_permission') }}</span>
                </a>
            </li>
            @endcanany

            @canany(['manage_other_interview_members', 'delegate_manage_delegations', 'escort_manage_delegations',
            'driver_manage_delegations', 'hotel_manage_delegations'])
            <li>
                <a href="{{ route('other-interview-members.index') }}"
                    class="{{ areActiveRoutes(['other-interview-members.create', 'otherInterviewMembers.edit', 'other-interview-members.index']) }}">
                   <svg class="pe-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="36" height="30" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M14.7141 15h4.268c.4043 0 .732-.3838.732-.8571V3.85714c0-.47338-.3277-.85714-.732-.85714H6.71411c-.55228 0-1 .44772-1 1v4m10.99999 7v-3h3v3h-3Zm-3 6H6.71411c-.55228 0-1-.4477-1-1 0-1.6569 1.34315-3 3-3h2.99999c1.6569 0 3 1.3431 3 3 0 .5523-.4477 1-1 1Zm-1-9.5c0 1.3807-1.1193 2.5-2.5 2.5s-2.49999-1.1193-2.49999-2.5S8.8334 9 10.2141 9s2.5 1.1193 2.5 2.5Z"/>
</svg>

                    <span class="text-md">{{ __db('interview_members') }}</span>
                </a>
            </li>
            @endcanany

            @canany(['manage_labels'])
            <li>
                <a href="{{ route('translations.index') }}"
                    class="{{ areActiveRoutes(['translations.create', 'translations.edit', 'translations.index']) }}">
  <svg class="pe-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="36" height="30" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m13 19 3.5-9 3.5 9m-6.125-2h5.25M3 7h7m0 0h2m-2 0c0 1.63-.793 3.926-2.239 5.655M7.5 6.818V5m.261 7.655C6.79 13.82 5.521 14.725 4 15m3.761-2.345L5 10m2.761 2.655L10.2 15"/>
</svg>

                    <span class="text-md">{{ __db('label_translations') }}</span>
                </a>
            </li>
            @endcanany

            <hr>
            <li>
                <a href="#">
                    <svg class="pe-2" width="36" height="30" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span class="text-md">{{ __db('profile') }}</span>
                </a>
            </li>
            </li>
            <li>
                <a href="{{ route('admin.logout') }}">
                    <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                    </svg>
                    <span class="text-md">{{ __db('logout') }}</span>
                </a>
            </li>
        </ul>
    </div>
</aside>