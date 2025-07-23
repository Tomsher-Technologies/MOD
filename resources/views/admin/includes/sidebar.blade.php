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

            <li>
                <a href="{{ route('staffs.index') }}" class="{{ areActiveRoutes(['staffs.index']) }}">
                    <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    <span class="text-lg">{{ __db('staffs') }}</span>
                </a>
            </li>
            @can('manage_roles')
                <li>
                    <a href="{{ route('roles.index') }}" class="{{ areActiveRoutes(['roles.create', 'roles.edit', 'roles.index']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('roles_and_permission') }}</span>
                    </a>
                </li>
            @endcan

            @can('manage_roles')
                <li>
                    <a href="{{ route('translations.index') }}" class="{{ areActiveRoutes(['translations.create', 'translations.edit', 'translations.index']) }}">
                        <svg class="pe-2" width="36" height="30" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-lg">{{ __db('label_translations') }}</span>
                    </a>
                </li>
            @endcan

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
                <a href="{{ route('admin.logout') }}">
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