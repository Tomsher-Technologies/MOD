<div x-data="{ isArrivalEditModalOpen: false }">
    <div class="flex items-center justify-between gap-4 mb-4">
        <form class="flex gap-4 w-full" action="{{ route('delegations.arrivalsIndex') }}" method="GET">
            @foreach (request()->except(['date_range', 'search_key', 'page']) as $k => $v)
                @if (is_array($v))
                    @foreach ($v as $vv)
                        <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endif
            @endforeach
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

                <div class="flex items-center justify-between  w-full">
                    <h2 class="font-semibold mb-0 !text-[22px] hide-when-fullscreen">{{ __db('arrivals') }}</h2>

                    <div class="flex items-center gap-4  w-full justify-between  mb-5">

                        <div class="flex gap-5 items-center ">
                            <div class="full-screen-logo hidden items-start">
                                <img src="{{ getAdminEventLogo() }}" alt="" class="max-h-[100px]">
                            </div>
                        </div>

                        <h2 class="font-semibold mb-0 !text-[22px] hidden full-screen-logo ">{{ __db('arrivals') }}
                        </h2>


                        <div class="full-screen-logo gap-8 !justify-between hidden items-end ">
                            <img src="{{ asset('assets/img/md-logo.svg') }}" class="light-logo max-h-[100px]"
                                alt="Logo">
                        </div>
                    </div>
                </div>

                <div class="flex items-end justify-end mb-3">
                    <a href="#" id="fullscreenToggleBtn"
                        class="!px-4 flex items-center gap-4 py-2 text-nowrap text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100 hover:text-[#B68A35] focus:z-10 focus:ring-4 focus:ring-gray-10">
                        <span>{{ __db('go_fullscreen') }}</span>
                    </a>
                </div>

                <hr class=" border-neutral-200 h-5 ">

                @include('shared-pages.arrivals.table')
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

        @foreach (request()->except(['invitation_from', 'continent_id', 'country_id', 'airport_id', 'status', 'page']) as $k => $v)
            @if (is_array($v))
                @foreach ($v as $vv)
                    <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endif
        @endforeach

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
        function updateFontSize() {
            const fontSize = document.getElementById('font-size').value;
            const url = new URL(window.location);
            url.searchParams.set('font_size', fontSize);
            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true
            });

            const preselectedContinents = @json(request('continent_id') ?? []);
            const preselectedCountries = @json(request('country_id') ?? []);

            const $continentSelect = $('#continent-select');
            const $countrySelect = $('#country-select');

            $continentSelect.on('change', function() {
                const continentIds = $(this).val();
                $countrySelect.find('option').remove();

                if (continentIds && continentIds.length > 0) {
                    $.get('{{ route('countries.by-continent') }}', {
                        continent_ids: continentIds
                    }, function(data) {
                        $.each(data, function(index, country) {
                            const isSelected = preselectedCountries.includes(country.id
                                .toString());
                            $countrySelect.append(new Option(country.name, country.id,
                                false, isSelected));
                        });

                        $countrySelect.trigger('change.select2');
                    }).fail(function() {
                        console.log('Failed to load countries');
                    });
                } else {
                    $countrySelect.val(null).trigger('change.select2');
                }
            });

            if (preselectedContinents.length > 0) {
                $continentSelect.val(preselectedContinents).trigger('change');
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
