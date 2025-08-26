<div x-data="{ isArrivalEditModalOpen: false }">
    <div class="flex items-center justify-between gap-12 mb-4">

        <input type="date"
            class="p-3 !w-[20%] text-secondary-light !border-[#d1d5db] rounded-lg w-full border text-sm">
        <form class="w-[75%]" action="{{ getRouteForPage('delegation.arrivalsIndex') }}" method="GET">
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="default-search" name="search_key" value="{{ request('search_key') }}"
                    class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg "
                    placeholder="{{ __db('Search by Delegation ID, Escorts, Drivers, Flight Number, Flight Name') }}" />
                <button type="submit"
                    class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __db('search') }}</button>
            </div>
        </form>
        <div class="text-center">
            <button
                class="text-white flex items-center gap-1 !bg-[#B68A35] hover:bg-[#A87C27] focus:ring-4 focus:ring-yellow-300 font-sm rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                type="button" data-drawer-target="filter-drawer" data-drawer-show="filter-drawer"
                aria-controls="filter-drawer">
                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                        d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
                </svg>
                <span>{{ __db('filter') }}</span>
            </button>
        </div>
    </div>
    <!-- Escorts -->
    <!-- Arrival Section -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full " id="fullDiv">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">


                <div class="flex items-center justify-between mb-5">

                    <h2 class="font-semibold mb-0 !text-[22px] mb-10 pb-4">{{ __db('arrivals') }}</h2>

                    <div class="full-screen-logo flex items-center gap-8 hidden">
                        <img src="{{ getAdminEventLogo() }}" alt="">
                        <img src="{{ asset('assets/img/md-logo.svg') }}" class="light-logo" alt="Logo">
                    </div>


                    <a href="#" id="fullscreenToggleBtn"
                        class="px-4 flex items-center gap-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100 hover:text-[#B68A35] focus:z-10 focus:ring-4 focus:ring-gray-10">
                        <span>{{ __db('go_fullscreen') }}</span>
                    </a>

                </div>

                <hr class="mx-6 border-neutral-200 h-5 ">
                @php

                    $statusLabels = [
                        'arrived' => __db('arrived'),
                        'to_be_arrived' => __db('to_be_arrived'),
                    ];

                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'render' => fn($row, $key) => $key +
                                1 +
                                ($arrivals->currentPage() - 1) * $arrivals->perPage(),
                        ],
                        [
                            'label' => __db('delegation'),
                            'render' => fn($row) => $row->delegate->delegation->code ?? '-',
                        ],
                        [
                            'label' => __db('continent'),
                            'render' => fn($row) => $row->delegate->delegation->continent->value ?? '-',
                        ],
                        [
                            'label' => __db('country'),
                            'render' => fn($row) => $row->delegate->delegation->country->value ?? '-',
                        ],
                        ['label' => __db('delegates'), 'render' => fn($row) => $row->delegate->name_en ?? '-'],
                        [
                            'label' => __db('escorts'),
                            'render' => function ($row) {
                                return $row->delegate->delegation->escorts->isNotEmpty()
                                    ? $row->delegate->delegation->escorts
                                        ->map(fn($escort) => e($escort->code))
                                        ->implode('<br>')
                                    : '-';
                            },
                        ],
                        [
                            'label' => __db('drivers'),
                            'render' => function ($row) {
                                return $row->delegate->delegation->drivers->isNotEmpty()
                                    ? $row->delegate->delegation->drivers
                                        ->map(fn($drivers) => e($drivers->code))
                                        ->implode('<br>')
                                    : '-';
                            },
                        ],
                        ['label' => __db('to_airport'), 'render' => fn($row) => $row->airport->value ?? '-'],
                        [
                            'label' => __db('date_time'),
                            'render' => fn($row) => $row->date_time
                                ? \Carbon\Carbon::parse($row->date_time)->format('Y-m-d h:i A')
                                : '-',
                        ],
                        [
                            'label' => __db('flight') . ' ' . __db('number'),
                            'render' => fn($row) => $row->flight_no ?? '-',
                        ],
                        [
                            'label' => __db('flight') . ' ' . __db('name'),
                            'render' => fn($row) => $row->flight_name ?? '-',
                        ],
                        [
                            'label' => __db('arrival') . ' ' . __db('status'),
                            'render' => function ($row) use ($statusLabels) {
                                return $row->status ?? '-';
                            },
                        ],
                        [
                            'label' => __db('actions'),
                            'render' => function ($row) {
                                $arrivalData = [
                                    'id' => $row->id,
                                    'airport_id' => $row->airport_id,
                                    'flight_no' => $row->flight_no,
                                    'flight_name' => $row->flight_name,
                                    'date_time' => $row->date_time
                                        ? \Carbon\Carbon::parse($row->date_time)->format('Y-m-d\TH:i')
                                        : '',
                                    'status' => $row->status,
                                ];
                                $json = htmlspecialchars(json_encode($arrivalData), ENT_QUOTES, 'UTF-8');
                                return '<button type="button" class="edit-arrival-btn text-[#B68A35]" data-arrival=\'' .
                                    $json .
                                    '\'>                                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z" fill="#000"></path></svg>
                                </button>';
                            },
                        ],
                    ];

                    $bgClass = [
                        'Arrived' => 'bg-[#b7e9b2]',
                        'Departed' => 'bg-[#c2e0ff]',
                        'Upcoming' => 'bg-[#fff9b2]',
                    ];

                    $rowClass = function ($row) use ($bgClass) {
                        $now = \Carbon\Carbon::now();
                        $statusName = $row->status->value ?? null;

                        if (!$row->date_time) {
                            return $bgClass[$statusName] ?? 'bg-[#fff]';
                        }

                        $rowDateTime = \Carbon\Carbon::parse($row->date_time);

                        if ($rowDateTime->lt($now->copy()->subHour())) {
                            return 'bg-[#b7e9b2]';
                        }
                        if ($rowDateTime->between($now->copy()->subHour(), $now->copy()->addHour())) {
                            return 'bg-[#ffc5c5]';
                        }
                        return $bgClass[$statusName] ?? 'bg-[#fff]';
                    };
                @endphp

                <x-reusable-table :columns="$columns" :data="$arrivals" :row-class="$rowClass" />
            </div>
        </div>
    </div>

</div>

<!-- Arrival Edit Modal -->

<div x-data="{
    isArrivalEditModalOpen: false,
    arrival: {},
    open(data) {
        this.arrival = data;
        this.isArrivalEditModalOpen = true;
    },
    close() {
        this.isArrivalEditModalOpen = false;
    }
}" x-on:open-edit-arrival.window="open($event.detail)">
    <div x-show="isArrivalEditModalOpen" x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" style="display: none;">
        <div class="bg-white rounded-lg w-[70%] p-6">
            <div class="flex items-start justify-between border-b pb-2 mb-4">
                <h3 class="text-xl font-semibold">{{ __db('edit') . ' ' . __db('arrival') }}</h3>
                <button @click="close" class="text-gray-400 hover:text-gray-900">
                    ✕
                </button>
            </div>

            <form :action="`{{ url('mod-admin/travel-update') }}/${arrival.id}`" method="POST" class="space-y-6"
                data-ajax-form="true">
                @csrf
                @method('POST')

                <div class="grid grid-cols-5 gap-5">
                    <div>
                        <label class="form-label">{{ __db('arrival') . ' ' . __db('airport') }}:</label>
                        <select name="airport_id" x-model="arrival.airport_id"
                            class="p-3 rounded-lg w-full border text-sm">
                            <option value="">{{ __db('select') . ' ' . __db('to') . ' ' . __db('airport') }}
                            </option>
                            @foreach (getDropdown('airports')->options as $option)
                                <option value="{{ $option->id }}">{{ $option->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">{{ __db('flight') . ' ' . __db('number') }}:</label>
                        <input type="text" name="flight_no" x-model="arrival.flight_no"
                            class="p-3 rounded-lg w-full border text-sm">
                    </div>

                    <div>
                        <label class="form-label">{{ __db('flight') . ' ' . __db('name') }}:</label>
                        <input type="text" name="flight_name" x-model="arrival.flight_name"
                            class="p-3 rounded-lg w-full border text-sm">
                    </div>

                    <div>
                        <label class="form-label">{{ __db('date_time') }}:</label>
                        <input type="datetime-local" name="date_time" x-model="arrival.date_time"
                            class="p-3 rounded-lg w-full border text-sm">
                    </div>
                    <div>

                        <label class="form-label">{{ __db('arrival') . ' ' . __db('status') }}:</label>
                        @php
                            $arrivalStatuses = [
                                'arrived' => __db('arrived'),
                                'to_be_arrived' => __db('to_be_arrived'),
                            ];
                        @endphp
                        <select name="status" x-model="arrival.status"
                            class="p-3 rounded-lg w-full border border-neutral-300 text-sm" required>
                            <option value="">{{ __db('select_status') }}</option>
                            @foreach ($arrivalStatuses as $value => $label)
                                <option :selected="arrival.status === '{{ $value }}'"
                                    value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="close" class="btn border px-4 py-2">{{ __db('cancel') }}</button>
                    <button type="submit"
                        class="btn !bg-[#B68A35] text-white px-6 py-2">{{ __db('update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="filter-drawer"
    class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80"
    tabindex="-1" aria-labelledby="drawer-label">
    <h5 id="drawer-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500">
        {{ __db('filter') }}</h5>
    <button type="button" data-drawer-hide="filter-drawer" aria-controls="filter-drawer"
        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 flex items-center justify-center">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
        <span class="sr-only">{{ __db('close_menu') }}</span>
    </button>

    <form action="{{ getRouteForPage('delegation.arrivalsIndex') }}" method="GET">
        <div class="flex flex-col gap-4 mt-4">
            <select name="invitation_from[]" placeholder="Invitation From" multiple
                class="select2 w-full p-3 text-secondary-light rounded-lg border border-gray-300 text-sm">
                @foreach (getDropDown('internal_ranking')->options as $option)
                    <option value="{{ $option->id }}" @if (in_array($option->id, request('invitation_from', []))) selected @endif>
                        {{ $option->value }}
                    </option>
                @endforeach
            </select>
            <select name="continent_id"
                class="w-full bg-white !py-3 text-sm !px-6 rounded-lg border text-secondary-light">
                <option value="">{{ __db('all_continents') }}</option>
                @foreach (getDropDown('continents')->options as $continent)
                    <option value="{{ $continent->id }}"
                        {{ request('continent_id') == $continent->id ? 'selected' : '' }}>
                        {{ $continent->value }}
                    </option>
                @endforeach
            </select>
            <select name="country_id"
                class="w-full bg-white !py-3 text-sm !px-6 rounded-lg border text-secondary-light">
                <option value="">{{ __db('all_countries') }}</option>
                @foreach (getDropDown('country')->options as $country)
                    <option value="{{ $country->id }}"
                        {{ request('country_id') == $country->id ? 'selected' : '' }}>
                        {{ $country->value }}
                    </option>
                @endforeach
            </select>
            <select name="airport_id"
                class="w-full bg-white !py-3 text-sm !px-6 rounded-lg border text-secondary-light">
                <option value="">{{ __db('all_airports') }}</option>
                @foreach (getDropDown('airports')->options as $airport)
                    <option value="{{ $airport->id }}"
                        {{ request('airport_id') == $airport->id ? 'selected' : '' }}>
                        {{ $airport->value }}
                    </option>
                @endforeach
            </select>
            <select name="status_id"
                class="w-full bg-white !py-3 text-sm !px-6 rounded-lg border text-secondary-light">
                <option value="">{{ __db('all_arrival_statuses') }}</option>
                @foreach (getDropDown('arrival_status')->options as $status)
                    <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                        {{ $status->value }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-6">
            <a href="{{ getRouteForPage('delegation.arrivalsIndex') }}"
                class="px-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100">Reset</a>
            <button type="submit"
                class="justify-center inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]">{{ __db('filter') }}</button>
        </div>
    </form>
</div>


@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.edit-arrival-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const arrival = JSON.parse(btn.getAttribute('data-arrival'));
                    window.dispatchEvent(new CustomEvent('open-edit-arrival', {
                        detail: arrival
                    }));
                });
            });
        });
    </script>



    <script>
        const fullscreenDiv = document.getElementById('fullDiv');

        $('#fullscreenToggleBtn').on('click', function() {
            const isInFullscreen =
                document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.mozFullScreenElement ||
                document.msFullscreenElement;

            if (!isInFullscreen) {
                // Enter fullscreen
                if (fullscreenDiv.requestFullscreen) {
                    fullscreenDiv.requestFullscreen();
                } else if (fullscreenDiv.webkitRequestFullscreen) {
                    fullscreenDiv.webkitRequestFullscreen();
                } else if (fullscreenDiv.msRequestFullscreen) {
                    fullscreenDiv.msRequestFullscreen();
                }
            } else {
                // Exit fullscreen
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        });

        // Listen for fullscreen changes
        $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
            const isInFullscreen =
                document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.mozFullScreenElement ||
                document.msFullscreenElement;

            if (isInFullscreen) {
                $('.hide-when-fullscreen').hide();
                $('#fullscreenToggleBtn').text('Exit Fullscreen');
            } else {
                $('.hide-when-fullscreen').show();
                $('#fullscreenToggleBtn').text('Go Fullscreen');
            }
        });


        // Listen for fullscreen changes
        $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
            const isInFullscreen =
                document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.mozFullScreenElement ||
                document.msFullscreenElement;

            if (isInFullscreen) {
                $('.hide-when-fullscreen').hide();
                $('.full-screen-logo').css('display', 'flex'); // SHOW during fullscreen
                $('#fullscreenToggleBtn').text('Exit Fullscreen');
            } else {
                $('.hide-when-fullscreen').show();
                $('.full-screen-logo').css('display', 'none'); // HIDE when not in fullscreen
                $('#fullscreenToggleBtn').text('Go Fullscreen');
            }
        });
    </script>
@endsection
