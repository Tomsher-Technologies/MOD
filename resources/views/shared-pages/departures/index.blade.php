@extends('layouts.admin_account', ['title' => __db('departures')])

@section('content')
    <div x-data="{ isDepartureEditModalOpen: false }">

        <div class="flex items-center justify-between gap-12 mb-4">

            <input type="date"
                class="p-3 !w-[20%] text-secondary-light !border-[#d1d5db] rounded-lg w-full border text-sm">
            <form class="w-[75%]" action="{{ route('delegations.departuresIndex') }}" method="GET">
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

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full " id="fullDiv">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

                    <div class="flex items-center justify-between mb-5">
                        <h2 class="font-semibold mb-0 !text-[22px] mb-10 pb-4">{{ __db('departures') }}</h2>
                        <div class="full-screen-logo flex items-center gap-8 hidden">
                            <img src="{{ getAdminEventLogo() }}" alt="">
                            <img src="{{ asset('assets/img/md-logo.svg') }}" class="light-logo" alt="Logo">
                        </div>


                        <a href="#" id="fullscreenToggleBtn"
                            class="px-4 flex items-center gap-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100 hover:text-[#B68A35] focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                            <span>{{ __db('go_fullscreen') }}</span>
                        </a>

                    </div>

                    <hr class="mx-6 border-neutral-200 h-5 ">
                    @php
                        $columns = [
                            [
                                'label' => __db('sl_no'),
                                'render' => fn($row, $key) => $key +
                                    1 +
                                    ($departures->currentPage() - 1) * $departures->perPage(),
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
                            [
                                'label' => __db('delegates'),
                                'render' => fn($row) => $row->delegate->name_en ?? '-',
                            ],
                            [
                                'label' => __db('escorts'),
                                'render' => fn($row) => $row->delegate->escort->name_en ?? '-',
                            ],
                            [
                                'label' => __db('drivers'),
                                'render' => fn($row) => $row->delegate->driver->name_en ?? '-',
                            ],
                            [
                                'label' => __db('from_airport'),
                                'render' => fn($row) => $row->airport->value ?? '-',
                            ],
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
                                'label' => __db('departure') . ' ' . __db('status'),
                                'render' => fn($row) => $row->status ?? '-',
                            ],
                            [
                                'label' => __db('actions'),
                                'permission' => ['add_travels', 'delegate_edit_delegations'],
                                'render' => function ($row) {
                                    $departureData = [
                                        'id' => $row->id,
                                        'airport_id' => $row->airport_id,
                                        'flight_no' => $row->flight_no,
                                        'flight_name' => $row->flight_name,
                                        'date_time' => $row->date_time
                                            ? \Carbon\Carbon::parse($row->date_time)->format('Y-m-d\TH:i')
                                            : '',
                                        'status' => $row->status,
                                    ];
                                    $json = htmlspecialchars(json_encode($departureData), ENT_QUOTES, 'UTF-8');
                                    return '<button type="button" class="edit-departure-btn text-[#B68A35]" data-departure=\'' .
                                        $json .
                                        '\'>                                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z" fill="#000"></path></svg>
                                </button>';
                                },
                            ],
                        ];
                        $rowClass = function ($row) {
                            if (!$row->date_time) {
                                return 'bg-[#ffffff]';
                            }

                            $now = \Carbon\Carbon::now();
                            $oneHourAgo = $now->copy()->subHour();
                            $oneHourHence = $now->copy()->addHour();
                            $rowDateTime = \Carbon\Carbon::parse($row->date_time);

                            $statusName =
                                is_object($row->status) && isset($row->status->value)
                                    ? $row->status->value
                                    : $row->status;

                            if ($statusName === 'to_be_departed') {
                                if ($rowDateTime->between($oneHourAgo, $oneHourHence)) {
                                    return 'bg-[#ffc5c5]';
                                }
                                if ($rowDateTime->gt($oneHourHence)) {
                                    return 'bg-[#ffffff]';
                                }
                                return 'bg-[#ffffff]';
                            }

                            if ($statusName === 'departed') {
                                return 'bg-[#b7e9b2]';
                            }

                            return 'bg-[#ffffff]';
                        };
                    @endphp
                    <x-reusable-table :columns="$columns" :enableRowLimit="true" :data="$departures" :row-class="$rowClass" />

                    <div class="mt-3 flex items-center flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <div class="h-5 w-5 bg-[#ffc5c5] rounded border"></div>
                            <span class="text-gray-800 text-sm">{{ __db('To be departed (within 1 hour)') }}</span>
                        </div>



                        <div class="flex items-center gap-2">
                            <div class="h-5 w-5 bg-[#b7e9b2] rounded border"></div>
                            <span class="text-gray-800 text-sm">{{ __db('Departed') }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="h-5 w-5 bg-[#ffffff] rounded border border-gray-300"></div>
                            <span class="text-gray-800 text-sm">{{ __db('Scheduled / No active status') }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Departure Edit Modal -->
    <div x-data="{
        isDepartureEditModalOpen: false,
        departure: {},
        open(data) {
            this.departure = data;
            this.isDepartureEditModalOpen = true;
        },
        close() {
            this.isDepartureEditModalOpen = false;
        }
    }" x-on:open-edit-departure.window="open($event.detail)">
        <div x-show="isDepartureEditModalOpen" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" style="display: none;">
            <div class="bg-white rounded-lg w-[70%] p-6">
                <div class="flex items-start justify-between border-b pb-2 mb-4">
                    <h3 class="text-xl font-semibold">{{ __db('edit') . ' ' . __db('departure') }}</h3>
                    <button @click="close" class="text-gray-400 hover:text-gray-900">
                        ✕
                    </button>
                </div>

                <form :action="`{{ url('mod-admin/travel-update') }}/${departure.id}`" method="POST" class="space-y-6"
                    data-ajax-form="true">
                    @csrf
                    @method('POST')

                    <div class="grid grid-cols-5 gap-5">
                        <div>
                            <label class="form-label">{{ __db('departure') . ' ' . __db('airport') }}:</label>
                            <select name="airport_id" x-model="departure.airport_id"
                                class="p-3 rounded-lg w-full border text-sm">
                                <option value="">{{ __db('select') . ' ' . __db('from') . ' ' . __db('airport') }}
                                </option>
                                @foreach (getDropdown('airports')->options as $option)
                                    <option value="{{ $option->id }}">{{ $option->value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label">{{ __db('flight') . ' ' . __db('number') }}:</label>
                            <input type="text" name="flight_no" x-model="departure.flight_no"
                                class="p-3 rounded-lg w-full border text-sm">
                        </div>

                        <div>
                            <label class="form-label">{{ __db('flight') . ' ' . __db('name') }}:</label>
                            <input type="text" name="flight_name" x-model="departure.flight_name"
                                class="p-3 rounded-lg w-full border text-sm">
                        </div>

                        <div>
                            <label class="form-label">{{ __db('date_time') }}:</label>
                            <input type="datetime-local" name="date_time" x-model="departure.date_time"
                                class="p-3 rounded-lg w-full border text-sm">
                        </div>

                        <div>
                            <label class="form-label">{{ __db('departure') . ' ' . __db('status') }}:</label>
                            @php
                                $departureStatuses = [
                                    'departed' => __db('departed'),
                                    // 'to_be_departed' => __db('to_be_departed'),
                                ];
                            @endphp
                            <select name="status" x-model="departure.status"
                                class="p-3 rounded-lg w-full border border-neutral-300 text-sm" required>
                                <option value="">{{ __db('select_status') }}</option>
                                @foreach ($departureStatuses as $value => $label)
                                    <option :selected="departure.status === '{{ $value }}'"
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

        <form action="{{ route('delegations.departuresIndex') }}" method="GET">
            <div class="flex flex-col gap-4 mt-4">


                <div class="flex flex-col">
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('invitation_from') }}</label>
                    <select name="invitation_from[]" multiple data-placeholder="{{ __db('select') }}"
                        class="select2 w-full rounded-lg border border-gray-300 text-sm">
                        <option value="">{{ __db('select') }}</option>
                        @foreach (getDropDown('departments')->options as $option)
                            <option value="{{ $option->id }}" @if (in_array($option->id, request('invitation_from', []))) selected @endif>
                                {{ $option->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="form-label block text-gray-700 font-medium">{{ __db('all_continents') }}</label>
                    <select multiple name="continent_id[]" id="continent-select" data-placeholder="{{ __db('select') }}"
                        class="select2 w-full rounded-lg border border-gray-300 text-sm">
                        <option value="">{{ __db('select') }}</option>
                        @foreach (getDropDown('continents')->options as $continent)
                            <option value="{{ $continent->id }}"
                                {{ is_array(request('continent_id')) && in_array($continent->id, request('continent_id')) ? 'selected' : '' }}>
                                {{ $continent->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="form-label block text-gray-700 font-medium">{{ __db('all_countries') }}</label>
                    <select name="country_id[]" id="country-select" multiple data-placeholder="{{ __db('select') }}"
                        class="select2 w-full rounded-lg border border-gray-300 text-sm">
                        <option value="">{{ __db('select') }}</option>
                        @foreach (getAllCountries() as $option)
                            <option value="{{ $option->id }}"
                                {{ is_array(request('country_id')) && in_array($option->id, request('country_id')) ? 'selected' : '' }}>
                                {{ $option->name }}
                            </option>
                        @endforeach
                    </select>
                </div>



                <div class="flex flex-col">
                    <label class="form-label block text-gray-700 font-medium">{{ __db('airport') }}</label>
                    <select name="airport_id[]" multiple
                        data-placeholder="{{ __db('select') . ' ' . __db('airport_id') }}"
                        class="select2 w-full bg-white !py-3 text-sm !px-6 rounded-lg border text-secondary-light">
                        <option value="">{{ __db('all_airports') }}</option>
                        @foreach (getDropDown('airports')->options as $airport)
                            <option value="{{ $airport->id }}"
                                {{ is_array(request('airport_id')) && in_array($airport->id, request('airport_id')) ? 'selected' : '' }}>

                                {{ $airport->value }}
                            </option>
                        @endforeach
                    </select>
                </div>




                <div class="flex flex-col">

                    @php
                        $departureStatuses = [
                            // 'to_be_departed' => __db('to_be_departed'),
                            'departed' => __db('departed'),
                        ];

                        $statuses = $departureStatuses;

                    @endphp

                    <label
                        class="form-label block text-gray-700 font-medium">{{ __db('arrival') . ' ' . __db('status') }}</label>
                    <select name="status[]" multiple data-placeholder="{{ __db('select') . ' ' . __db('status') }}"
                        class="select2 w-full bg-white !py-3 text-sm !px-6 rounded-lg border text-secondary-light">
                        <option value="">{{ __db('all_arrival_statuses') }}</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}"
                                {{ is_array(request('status')) && in_array($label, request('status')) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>



            </div>
            <div class="grid grid-cols-2 gap-4 mt-6">
                <a href="{{ route('delegations.departuresIndex') }}"
                    class="px-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100">{{ __db('reset') }}</a>
                <button type="submit"
                    class="justify-center inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]">{{ __db('filter') }}</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.edit-departure-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const departure = JSON.parse(btn.getAttribute('data-departure'));
                    window.dispatchEvent(new CustomEvent('open-edit-departure', {
                        detail: departure
                    }));
                });
            });

            // Handle continent change to load countries
            const continentSelect = $("#continent");
            const countrySelect = $("#country");

            continentSelect.on("change", async function() {
                const selectedContinent = $(this).val();

                // Clear current options except the default
                countrySelect.find('option[value!=""]').remove();

                if (selectedContinent) {
                    try {
                        let response = await fetch(
                            `/mod-admin/get-countries?continent_ids=${selectedContinent}`);
                        let countries = await response.json();

                        // Add new options
                        countries.forEach(country => {
                            let option = new Option(country.name, country.id, false, false);
                            countrySelect.append(option);
                        });

                        countrySelect.trigger("change");
                    } catch (error) {
                        console.error("Error fetching countries:", error);
                    }
                }
            });

            // Trigger continent change on page load if a continent is already selected
            const selectedContinent = continentSelect.val();
            if (selectedContinent) {
                continentSelect.trigger("change");
            }
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

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true
            });

            let initiallySelectedCountries = $('#country-select').val() || [];

            $('#continent-select').on('change', function() {
                const continentId = $(this).val();
                const countrySelect = $('#country-select');

                countrySelect.find('option[value!=""]').remove();

                if (continentId) {
                    $.get('{{ route('countries.by-continent') }}', {
                        continent_ids: continentId
                    }, function(data) {
                        $.each(data, function(index, country) {
                            const isSelected = initiallySelectedCountries.includes(country
                                .id.toString());

                            countrySelect.append(new Option(country.name, country.id, false,
                                isSelected));
                        });

                        countrySelect.trigger('change');
                    }).fail(function() {
                        console.log('Failed to load countries');
                    });
                } else {
                    countrySelect.val(null).trigger('change');
                }
            });

            const selectedContinent = $('#continent-select').val();
            if (selectedContinent && selectedContinent.length > 0) {
                $('#continent-select').trigger('change');
            }
        });
    </script>
@endsection
