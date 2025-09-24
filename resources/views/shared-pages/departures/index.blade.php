<div x-data="{ isDepartureEditModalOpen: false }">
    <div class="flex items-center justify-between gap-4 mb-4">
        <form class="flex gap-4 w-full" action="{{ route('delegations.departuresIndex') }}" method="GET">
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

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full " id="fullDiv1">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('departures') }}</h2>
                    <div class="full-screen-logo flex items-center gap-8 hidden">
                        <img src="{{ getAdminEventLogo() }}" alt="" class="max-h-[100px]">
                        <img src="{{ asset('assets/img/md-logo.svg') }}" class="light-logo max-h-[100px]"
                            alt="Logo">
                    </div>

                    <a href="#" id="fullscreenToggleBtn1"
                        class="px-4 flex items-center gap-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100 hover:text-[#B68A35] focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                        <span>{{ __db('go_fullscreen') }}</span>
                    </a>
                </div>

                <hr class=" border-neutral-200 h-5 ">

                @include('shared-pages.departures.table')

            </div>
        </div>
    </div>

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

                <form
                    :action="`{{ url('mod-events/travel-update') }}/${departure.ids && departure.ids.length > 0 ? departure.ids[0] : departure.id}`"
                    method="POST" class="space-y-6" data-ajax-form="true">
                    @csrf
                    @method('POST')

                    <template x-if="departure.ids">
                        <div>
                            <template x-for="id in departure.ids" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>
                        </div>
                    </template>

                    <div class="grid grid-cols-5 gap-5">
                        <div>
                            <label class="form-label">{{ __db('departure') . ' ' . __db('airport') }}:</label>
                            <select name="airport_id" x-model="departure.airport_id"
                                class="p-3 rounded-lg w-full border text-sm">
                                <option value="">
                                    {{ __db('select') . ' ' . __db('from') . ' ' . __db('airport') }}
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
                            <label class="form-label">{{ __db('date_time') }}:<span
                                    class="text-red-600">*</span></label>
                            <input type="text" name="date_time" x-model="departure.date_time"
                                id="departure_datetime"
                                class="p-3 rounded-lg w-full border text-sm datetimepicker-input">
                        </div>

                        <div>
                            <label class="form-label">{{ __db('departure') . ' ' . __db('status') }}:</label>
                            @php
                                $departureStatuses = [
                                    'departed' => __db('departed'),
                                    'to_be_departed' => __db('to_be_departed'),
                                ];
                            @endphp
                            <select disabled name="status" x-model="departure.status"
                                class="p-3 rounded-lg w-full border border-neutral-300 text-sm" required>
                                <option value="">{{ __db('select_status') }}</option>
                                @foreach ($departureStatuses as $value => $label)
                                    <option :selected="departure.status === '{{ $value }}'"
                                        value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ __db('field_automatically_managed_by_system') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="close"
                            class="btn border px-4 py-2">{{ __db('cancel') }}</button>
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
                    <label
                        class="form-label block mb-1 text-gray-700 font-medium">{{ __db('invitation_from') }}</label>
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
                    <select multiple name="continent_id[]" id="continent-select"
                        data-placeholder="{{ __db('select') }}"
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
                        $departureStatuses = [
                            'to_be_departed' => __db('to_be_departed'),
                            'departed' => __db('departed'),
                        ];

                        $statuses = $departureStatuses;

                    @endphp

                    <label
                        class="form-label block text-gray-700 font-medium">{{ __db('departure') . ' ' . __db('status') }}</label>
                    <select name="status[]" multiple data-placeholder="{{ __db('select') . ' ' . __db('status') }}"
                        class="select2 w-full bg-white !py-3 text-sm !px-6 rounded-lg border text-secondary-light">
                        <option value="">{{ __db('all_departure_statuses') }}</option>
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
                <a href="{{ route('delegations.departuresIndex') }}"
                    class="px-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100">{{ __db('reset') }}</a>
                <button type="submit"
                    class="justify-center inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]">{{ __db('filter') }}</button>
            </div>
        </form>
    </div>

    @section('script')
        <script>
            document.addEventListener("DOMContentLoaded", () => {

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

                // Initialize datetimepicker for departure modal
                const departureDateTimeInput = document.getElementById('departure_datetime');
                if (departureDateTimeInput) {
                    $(departureDateTimeInput).datetimepicker({
                        format: 'Y-m-d H:i',
                        step: 5,
                        inline: false,
                        todayButton: true,
                        hours24: true,
                        mask: true,
                    });
                }
            });

            function bindDepartureEditButtons() {
                document.querySelectorAll('.edit-departure-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const departure = JSON.parse(btn.getAttribute('data-departure'));
                        window.dispatchEvent(new CustomEvent('open-edit-departure', {
                            detail: departure
                        }));
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', bindDepartureEditButtons);
        </script>
    @endsection
