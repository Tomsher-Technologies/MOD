<div x-data="{ isArrivalEditModalOpen: false }">
    <div class="flex items-center justify-between gap-4 mb-4">
        <form class="flex gap-4 w-full" action="{{ route('delegations.arrivalsIndex') }}" method="GET">
            <div class="flex flex-col">
                <input type="text" class="form-control date-range" id="date_range" name="date_range"
                    placeholder="{{ 'date' }}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss"
                    data-separator=" to " autocomplete="off"
                    value="{{ request('date_range') ?? now()->format('d-m-Y') . ' to ' . now()->format('d-m-Y') }}">
            </div>
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="default-search" name="search_key" value="{{ request('search_key') }}"
                        class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg "
                        placeholder="{{ __db('transport_search_placeholder') }}" />
                </div>
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="!text-[#5D471D] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __db('search') }}</button>
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
                    <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('arrivals') }}</h2>

                    <div class="full-screen-logo flex items-center gap-8 hidden">
                        <img src="{{ getAdminEventLogo() }}" alt="">
                        <img src="{{ asset('assets/img/md-logo.svg') }}" class="light-logo" alt="Logo">
                    </div>

                    <a href="#" id="fullscreenToggleBtn"
                        class="px-4 flex items-center gap-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100 hover:text-[#B68A35] focus:z-10 focus:ring-4 focus:ring-gray-10">
                        <span>{{ __db('go_fullscreen') }}</span>
                    </a>
                </div>

                <hr class=" border-neutral-200 h-5 ">

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
                                (($paginator ?? $arrivals)->currentPage() - 1) * ($paginator ?? $arrivals)->perPage(),
                        ],
                        [
                            'label' => __db('delegation'),
                            'render' => function ($row) {
                                $delegationId = $row['delegation']->id ?? null;
                                $delegationCode = $row['delegation']->code ?? '-';

                                if ($delegationId && $delegationCode !== '-') {
                                    $viewUrl = route('delegations.show', $delegationId);
                                    return '<a href="' .
                                        $viewUrl .
                                        '" class="text-[#B68A35] hover:underline">' .
                                        e($delegationCode) .
                                        '</a>';
                                }

                                return $delegationCode;
                            },
                        ],
                        [
                            'label' => __db('continent'),
                            'render' => fn($row) => $row['delegation']->continent->value ?? '-',
                        ],
                        [
                            'label' => __db('country'),
                            'key' => 'country',
                            'render' => function ($row) {
                                if (!$row['delegation']->country) {
                                    return '-';
                                }

                                $flag = $row['delegation']->country->flag
                                    ? '<img src="' .
                                        getUploadedImage($row['delegation']->country->flag) .
                                        '" 
                                        alt="' .
                                        e($row['delegation']->country->name) .
                                        ' flag" 
                                        class="inline-block w-6 h-4 mr-2 rounded-sm object-cover" />'
                                    : '';

                                return $flag . ' ' . e($row['delegation']->country->name);
                            },
                        ],
                        [
                            'label' => __db('invitation_from'),
                            'render' => fn($row) => $row['delegation']->invitationFrom->value ?? '-',
                        ],
                        [
                            'label' => __db('delegates'),
                            'render' => function ($row) {
                                $delegation = $row['delegation'] ?? null;

                                if ($delegation) {
                                    $teamHead = null;
                                    foreach ($row['delegates'] as $delegate) {
                                        if ($delegate->team_head) {
                                            $teamHead = $delegate;
                                            break;
                                        }
                                    }
                                    
                                    if ($teamHead) {
                                        return e(
                                            $teamHead->getTranslation('title') .
                                                '. ' .
                                                $teamHead->getTranslation('name'),
                                        );
                                    }
                                    
                                    $firstDelegate = collect($row['delegates'])->first();
                                    if ($firstDelegate) {
                                        return e(
                                            $firstDelegate->getTranslation('title') .
                                                '. ' .
                                                $firstDelegate->getTranslation('name'),
                                        );
                                    }
                                }

                                return '-';
                            },
                        ],

                        [
                            'label' => __db('escorts'),
                            'render' => function ($row) {
                                return $row['delegation']->escorts->isNotEmpty()
                                    ? $row['delegation']->escorts->map(fn($escort) => e($escort->code))->implode('<br>')
                                    : '-';
                            },
                        ],
                        [
                            'label' => __db('drivers'),
                            'render' => function ($row) {
                                return $row['delegation']->drivers->isNotEmpty()
                                    ? $row['delegation']->drivers
                                        ->map(fn($drivers) => e($drivers->code))
                                        ->implode('<br>')
                                    : '-';
                            },
                        ],
                        ['label' => __db('to_airport'), 'render' => fn($row) => $row['airport']->value ?? '-'],
                        [
                            'label' => __db('date_time'),
                            'render' => fn($row) => $row['date_time']
                                ? \Carbon\Carbon::parse($row['date_time'])->format('Y-m-d H:i')
                                : '-',
                        ],
                        [
                            'label' => __db('flight') . ' ' . __db('number'),
                            'render' => fn($row) => $row['flight_no'] ?? '-',
                        ],
                        [
                            'label' => __db('flight') . ' ' . __db('name'),
                            'render' => fn($row) => $row['flight_name'] ?? '-',
                        ],
                        [
                            'label' => __db('arrival') . ' ' . __db('status'),
                            'render' => function ($row) use ($statusLabels) {
                                return $row['status'] ?? '-';
                            },
                        ],
                        [
                            'label' => __db('actions'),
                            'permission' => ['add_travels', 'delegate_add_delegates'],
                            'render' => function ($row) {
                                $transportIds = collect($row['transports'])->pluck('id')->toArray();
                                if (empty($transportIds)) {
                                    return '-';
                                }

                                $firstTransport = $row['transports'][0];
                                $delegationId = $row['delegation']->id ?? null;

                                $arrivalData = [
                                    'ids' => $transportIds,
                                    'airport_id' => $firstTransport->airport_id,
                                    'flight_no' => $firstTransport->flight_no,
                                    'flight_name' => $firstTransport->flight_name,
                                    'date_time' => $firstTransport->date_time
                                        ? \Carbon\Carbon::parse($firstTransport->date_time)->format('Y-m-d H:i')
                                        : '',
                                    'status' => $firstTransport->status,
                                ];
                                $json = htmlspecialchars(json_encode($arrivalData), ENT_QUOTES, 'UTF-8');

                                $viewButton = '';
                                if ($delegationId) {
                                    $viewUrl = route('delegations.show', $delegationId);
                                    $viewButton =
                                        '<a href="' .
                                        $viewUrl .
                                        '" class="w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center" title="' .
                                        __db('view_delegation') .
                                        '">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#B68A35" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>';
                                }

                                $editButton =
                                    '<button type="button" class="edit-arrival-btn w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center ml-1" data-arrival=\'' .
                                    $json .
                                    '\'><svg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 512 512\'><path d=\'M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z\' fill=\'#B68A35\'></path></svg>
                                </button>';

                                return $viewButton . $editButton;
                            },
                        ],
                    ];

                    $bgClass = [
                        'to_be_arrived' => 'bg-[#fff]',
                        'arrived' => 'bg-[#b7e9b2]',
                    ];

                    $rowClass = function ($row) use ($bgClass) {
                        $now = \Carbon\Carbon::now();

                        $statusName =
                            is_object($row['status']) && isset($row['status']->value)
                                ? $row['status']->value
                                : $row['status'];

                        if (!$row['date_time']) {
                            return $bgClass[$statusName] ?? 'bg-[#fff]';
                        }

                        $rowDateTime = \Carbon\Carbon::parse($row['date_time']);

                        if ($statusName === 'to_be_arrived') {
                            if ($rowDateTime->between($now->copy()->subHour(), $now->copy()->addHour())) {
                                return 'bg-[#ffc5c5]';
                            }

                            if ($rowDateTime->gt($now->copy()->addHour())) {
                                return 'bg-[#fff]';
                            }
                        }

                        if ($statusName === 'arrived') {
                            return $bgClass['arrived'];
                        }

                        return $bgClass[$statusName] ?? 'bg-[#fff]';
                    };
                @endphp

                <div id="arrivals-table-container">
                    <x-reusable-table :columns="$columns" :enableRowLimit="true" table-id="arrivals-table" 
                        :data="$paginator" :row-class="$rowClass" />
                </div>

                <div class="mt-3 flex items-center flex-wrap gap-4">
                    <div class="flex items-center gap-2">
                        <div class="h-5 w-5 bg-[#fff] rounded border border-gray-300"></div>
                        <span class="text-gray-800 text-sm">{{ __db('scheduled_no_active_status') }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="h-5 w-5 bg-[#b7e9b2] rounded border"></div>
                        <span class="text-gray-800 text-sm">{{ __db('arrived') }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="h-5 w-5 bg-[#ffc5c5] rounded border"></div>
                        <span class="text-gray-800 text-sm">{{ __db('to_be_arrived_within_1_hour') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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

            <form
                :action="`{{ url('mod-events/travel-update') }}/${arrival.ids && arrival.ids.length > 0 ? arrival.ids[0] : arrival.id}`"
                method="POST" class="space-y-6" data-ajax-form="true">
                @csrf
                @method('POST')

                <template x-if="arrival.ids">
                    <div>
                        <template x-for="id in arrival.ids" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                    </div>
                </template>

                <div class="grid grid-cols-5 gap-5">
                    <div>
                        <label class="form-label">{{ __db('arrival') . ' ' . __db('airport') }}:</label>
                        <select name="airport_id" x-model="arrival.airport_id"
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
                        <input type="text" name="flight_no" x-model="arrival.flight_no"
                            class="p-3 rounded-lg w-full border text-sm">
                    </div>

                    <div>
                        <label class="form-label">{{ __db('flight') . ' ' . __db('name') }}:</label>
                        <input type="text" name="flight_name" x-model="arrival.flight_name"
                            class="p-3 rounded-lg w-full border text-sm">
                    </div>

                    <div>
                        <label class="form-label">{{ __db('date_time') }}: <span
                                class="text-red-600">*</span></label>
                        <input type="text" name="date_time" x-model="arrival.date_time" id="arrival_datetime"
                            class="p-3 rounded-lg w-full border text-sm datetimepicker-input">
                    </div>
                    <div>

                        <label class="form-label">{{ __db('arrival') . ' ' . __db('status') }}:</label>
                        @php
                            $arrivalStatuses = [
                                'arrived' => __db('arrived'),
                                'to_be_arrived' => __db('to_be_arrived'),
                            ];
                        @endphp
                        <select disabled name="status" x-model="arrival.status"
                            class="p-3 rounded-lg w-full border border-neutral-300 text-sm" required>
                            <option value="">{{ __db('select_status') }}</option>
                            @foreach ($arrivalStatuses as $value => $label)
                                <option :selected="arrival.status === '{{ $value }}'"
                                    value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="text-xs text-gray-500 mt-1">{{ __db('field_automatically_managed_by_system') }}
                        </div>

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

    <form action="{{ route('delegations.arrivalsIndex') }}" method="GET">
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
                    {{-- @foreach (getAllCountries() as $option)
                        <option value="{{ $option->id }}"
                            {{ is_array(request('country_id')) && in_array($option->id, request('country_id')) ? 'selected' : '' }}>
                            {{ $option->name }}
                        </option>
                    @endforeach --}}
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
                    $arrivalStatuses = [
                        'arrived' => __db('arrived'),
                        'to_be_arrived' => __db('to_be_arrived'),
                    ];
                    $departureStatuses = [
                        'to_be_departed' => __db('to_be_departed'),
                        'departed' => __db('departed'),
                    ];

                    $statuses = $arrivalStatuses;

                @endphp

                <label
                    class="form-label block text-gray-700 font-medium">{{ __db('arrival') . ' ' . __db('status') }}</label>
                <select name="status[]" multiple data-placeholder="{{ __db('select') . ' ' . __db('status') }}"
                    class="select2 w-full bg-white !py-3 text-sm !px-6 rounded-lg border text-secondary-light">
                    <option value="">{{ __db('all_arrival_statuses') }}</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}"
                            {{ is_array(request('status')) && in_array($value, request('status')) ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-6">
            <a href="{{ route('delegations.arrivalsIndex') }}"
                class="px-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100">{{ __db('reset') }}</a>
            <button type="submit"
                class="justify-center inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]">{{ __db('filter') }}</button>
        </div>
    </form>
</div>


@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
            
            const arrivalDateTimeInput = document.getElementById('arrival_datetime');
            if (arrivalDateTimeInput) {
                $(arrivalDateTimeInput).datetimepicker({
                    format: 'Y-m-d H:i',
                    step: 5,
                    inline: false,
                    todayButton: true,
                    hours24: true,
                    mask: true,
                });
            }
        });
        
        function bindArrivalEditButtons() {
            document.querySelectorAll('.edit-arrival-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const arrival = JSON.parse(btn.getAttribute('data-arrival'));
                    window.dispatchEvent(new CustomEvent('open-edit-arrival', {
                        detail: arrival
                    }));
                });
            });
        }
        
        document.addEventListener('DOMContentLoaded', bindArrivalEditButtons);
    </script>
@endsection
