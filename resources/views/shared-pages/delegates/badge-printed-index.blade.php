<div x-data="{
    badgePrintedFilter: '{{ request('badge_printed', '') }}',
    init() {
        this.badgePrintedFilter = '{{ request('badge_printed', '') }}';
    },
    handleBadgePrintedChange(delegateId, isChecked) {
        if (!isChecked && originalBadgePrintedStatus[delegateId]) {
            updateBadgePrintedStatus(delegateId, isChecked);
        }
    }
}">

    <div class="flex items-center justify-between gap-4 mb-4">
        <h2 class="font-semibold mb-0 !text-[22px]">
            {{ __db('badge') . ' ' . __db('printed') . ' ' . __db('delegates') }}</h2>

    </div>

    <style>
        .team-head-indicator {
            background-color: #fee2e2;
            color: #991b1b;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            margin-left: 5px;
        }
    </style>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full " id="fullDiv">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <div class="flex items-center justify-between mb-5">


                    <form class="flex gap-4 w-full" action="{{ route('delegates.badgePrintedIndex') }}" method="GET">

                        @foreach (request()->except(['search_key', 'badge_printed', 'page']) as $k => $v)
                            @if (is_array($v))
                                @foreach ($v as $vv)
                                    <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach

                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-3 text-black" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="search" id="default-search" name="search_key"
                                    value="{{ request('search_key') }}"
                                    class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg "
                                    placeholder="{{ __db('badge_printed_search_placeholder') }}" />
                            </div>
                        </div>

                        <div class="flex items-end">
                            <select name="badge_printed" x-model="badgePrintedFilter"
                                class="p-2.5 rounded-lg border text-sm !border-[#d1d5db]">
                                <option value="">{{ __db('all_delegates') }}</option>
                                <option value="1" {{ request('badge_printed') == '1' ? 'selected' : '' }}>
                                    {{ __db('badge_printed') }}</option>
                                <option value="0" {{ request('badge_printed') == '0' ? 'selected' : '' }}>
                                    {{ __db('not_badge_printed') }}</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                class="!text-[#5D471D] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __db('search') }}</button>
                        </div>

                        @directCanany(['badge_print_export'])
                            @if (request('badge_printed') == '0')
                                <div class="flex items-center">
                                    <button type="button" onclick="exportSelectedDelegates()"
                                        class="!text-white !bg-[#B68A35] hover:bg-[#A87C27] focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                                        {{ __db('export_to_excel') }}
                                    </button>
                                </div>
                            @endif
                        @enddirectCanany

                    </form>

                    <div class="text-center">
                        <button
                            class="text-white mr-4 flex items-center gap-1 !bg-[#B68A35] hover:bg-[#A87C27] focus:ring-4 focus:ring-yellow-300 font-sm rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                            type="button" data-drawer-target="filter-drawer" data-drawer-show="filter-drawer"
                            aria-controls="filter-drawer">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                                    d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
                            </svg>
                            <span>{{ __db('filter') }}</span>
                        </button>
                    </div>




                    <div class="full-screen-logo flex items-center gap-8 hidden">
                        <img src="{{ getAdminEventLogo() }}" alt="">
                        <img src="{{ asset('assets/img/md-logo.svg') }}" class="light-logo" alt="Logo">
                    </div>

                    {{-- <a href="#" id="fullscreenToggleBtn"
                        class="px-4 flex items-center gap-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100 hover:text-[#B68A35] focus:z-10 focus:ring-4 focus:ring-gray-10">
                        <span>{{ __db('go_fullscreen') }}</span>
                    </a> --}}
                </div>

                <hr class=" border-neutral-200 h-5 ">

                @php
                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'render' => fn($row, $key) => $key +
                                1 +
                                ($delegates->currentPage() - 1) * $delegates->perPage(),
                        ],
                        [
                            'label' => __db('delegation'),
                            'render' => function ($row) {
                                $delegationUrl = route('delegations.show', $row->delegation);

                                return '
                                                 
                                                        <a href="' .
                                    $delegationUrl .
                                    '" class="font-medium !text-[#B68A35] hover:underline">
                                                            ' .
                                    $row->delegation->code .
                                    '
                                                        </a>

                                                        ';
                            },
                        ],
                        [
                            'label' => __db('delegate_name'),
                            'render' => function ($row) {
                                return $row->getTranslation('title') . '. ' . $row->getTranslation('name') ?? '-';
                            },
                        ],
                        [
                            'label' => __db('user_type'),
                            'render' => function ($row) {
                                return $row->team_head ? __db('team_head') : __db('member');
                            },
                        ],

                        [
                            'label' => __db('country'),
                            'render' => function ($row) {
                                if (!$row->delegation->country) {
                                    return '-';
                                }

                                $flag = $row->delegation->country->flag
                                    ? '<img src="' .
                                        getUploadedImage($row->delegation->country->flag) .
                                        '" 
                                        alt="' .
                                        e($row->delegation->country->name) .
                                        ' flag" 
                                        class="inline-block w-6 h-4 mr-2 rounded-sm object-cover" />'
                                    : '';

                                return $flag . ' ' . e($row->delegation->country->name);
                            },
                        ],
                        [
                            'label' => __db('continent'),
                            'render' => fn($row) => $row->delegation->continent->value ?? '-',
                        ],
                        [
                            'label' => __db('invitation_from'),
                            'render' => fn($row) => $row->delegation->invitationFrom->value ?? '-',
                        ],
                        [
                            'label' => __db('designation'),
                            'render' => fn($row) => $row->getTranslation('designation') ?? '-',
                        ],
                        [
                            'label' => __db('badge_printed'),
                            'render' => function ($row) {
                                $checked = $row->badge_printed ? 'checked' : '';
                                $teamHead = $row->team_head ? 'true' : 'false';
                                return '
                                <input type="checkbox" 
                                    data-delegate-id="' .
                                    $row->id .
                                    '" 
                                    data-team-head="' .
                                    $teamHead .
                                    '"
                                    ' .
                                    $checked .
                                    '
                                    onchange="handleBadgePrintedChange(' .
                                    $row->id .
                                    ', this.checked)"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 delegate-checkbox">
                            ';
                            },
                        ],
                    ];
                @endphp

                @if (request('badge_printed') == '0')
                    <div class="mb-4 flex justify-between">
                        <div></div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="select-all-checkbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="select-all-checkbox"
                                class="ml-2 text-sm font-bold text-gray-700">{{ __db('select') }}
                                {{ __db('all') }}</label>
                        </div>

                    </div>
                @endif

                <x-reusable-table :columns="$columns" :enableRowLimit="true" table-id="badge-printed-delegates-table"
                    :enableColumnListBtn="true" :data="$delegates" />

                <div class="mt-3 flex items-center flex-wrap gap-4">

                    <div class="mt-3 flex items-center flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <div class="h-5 w-5 bg-[#fee2e2] rounded"></div>
                            <span class="text-gray-800 text-sm">{{ __db('team_head') }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="h-5 w-5 bg-[#ffffff] rounded border border-gray-300"></div>
                            <span class="text-gray-800 text-sm">{{ __db('members') }}</span>
                        </div>
                    </div>
                </div>


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
        
        <div class="border-t border-gray-200 mt-4">
            <form action="{{ route('delegates.badgePrintedIndex') }}" method="GET" class="space-y-4">

                @foreach (request()->except(['continent_id', 'country_id', 'invitation_from', 'page']) as $k => $v)
                    @if (is_array($v))
                        @foreach ($v as $vv)
                            <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endif
                @endforeach

                @if (request('search_key'))
                    <input type="hidden" name="search_key" value="{{ request('search_key') }}">
                @endif
                @if (request('badge_printed') !== null)
                    <input type="hidden" name="badge_printed" value="{{ request('badge_printed') }}">
                @endif

                <div class="flex flex-col">
                    <label class="form-label block text-gray-700 font-medium">{{ __db('all_continents') }}</label>
                    <select multiple name="continent_id[]" id="continent-select"
                        data-placeholder="{{ __db('select') }}"
                        class="select2 w-full rounded-lg border border-gray-300 text-sm">
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
                    </select>
                </div>

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

                <div class="grid grid-cols-2 gap-4 mt-6">
                    <a href="{{ route('delegates.badgePrintedIndex') }}"
                        class="px-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100">{{ __db('reset') }}</a>
                    <button type="submit"
                        class="justify-center inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]">{{ __db('filter') }}</button>
                </div>
            </form>
        </div>
    </div>


</div>

@section('script')
    <script>
        function badgePrintedComponent() {
            return {
                badgePrintedFilter: @json(request('badge_printed', '')),
                init() {
                    this.badgePrintedFilter = @json(request('badge_printed', ''));
                },
                updateBadgePrintedStatus(delegateId, isChecked) {
                    fetch('{{ route('delegates.updateBadgePrintedStatus') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                delegate_id: delegateId,
                                badge_printed: isChecked
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                toastr(data.message);
                            } else {
                                toastr(data.message);
                                const checkbox = document.querySelector(`input[data-delegate-id="${delegateId}"]`);
                                if (checkbox) {
                                    checkbox.checked = !isChecked;
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            const checkbox = document.querySelector(`input[data-delegate-id="${delegateId}"]`);
                            if (checkbox) {
                                checkbox.checked = !isChecked;
                            }
                            toastr.error("{{ __db('error_occured') }}");
                        });
                }
            }
        }
    </script>
    <script>
        let originalBadgePrintedStatus = {};

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[data-delegate-id]').forEach(checkbox => {
                const delegateId = checkbox.getAttribute('data-delegate-id');
                originalBadgePrintedStatus[delegateId] = checkbox.checked;
            });

            if ("{{ request('badge_printed') }}" == "0") {
                const selectAllCheckbox = document.getElementById('select-all-checkbox');
                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        const checkboxes = document.querySelectorAll('.delegate-checkbox');
                        checkboxes.forEach(checkbox => {
                            if ((this.checked && !checkbox.checked) || (!this.checked && checkbox
                                    .checked)) {
                                checkbox.checked = this.checked;
                            }
                        });
                    });
                }
            }

            setTimeout(function() {
                document.querySelectorAll('.delegate-checkbox').forEach(checkbox => {
                    if (checkbox.hasAttribute('data-team-head') && checkbox.getAttribute(
                            'data-team-head') === 'true') {
                        const row = checkbox.closest('tr');
                        if (row) {
                            row.style.backgroundColor = '#fee2e2';
                        }
                    }
                });
            }, 100);
        });

        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('delegate-checkbox')) {
                const allCheckboxes = document.querySelectorAll('.delegate-checkbox');
                const selectAllCheckbox = document.getElementById('select-all-checkbox');

                if (selectAllCheckbox) {
                    const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            }
        });

        function handleBadgePrintedChange(delegateId, isChecked) {
            if (!isChecked && originalBadgePrintedStatus[delegateId]) {
                updateBadgePrintedStatus(delegateId, isChecked);
            }
        }

        function updateBadgePrintedStatus(delegateId, isChecked) {
            fetch('{{ route('delegates.updateBadgePrintedStatus') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        delegate_id: delegateId,
                        badge_printed: isChecked
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        originalBadgePrintedStatus[delegateId] = isChecked;
                        toastr.success(data.message);
                    } else {
                        toastr.success(data.message);
                        const checkbox = document.querySelector(`input[data-delegate-id="${delegateId}"]`);
                        if (checkbox) {
                            checkbox.checked = !isChecked;
                            originalBadgePrintedStatus[delegateId] = !isChecked;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const checkbox = document.querySelector(`input[data-delegate-id="${delegateId}"]`);
                    if (checkbox) {
                        checkbox.checked = !isChecked;
                        originalBadgePrintedStatus[delegateId] = !isChecked;
                    }
                    toastr.error('{{ __db('error_occured') }}');
                });
        }

        function exportBadgePrintedDelegates() {
            const url = new URL('{{ route('delegates.exportBadgePrintedDelegates') }}', window.location.origin);

            const badgePrintedFilter = document.querySelector('select[name="badge_printed"]');
            if (badgePrintedFilter) {
                url.searchParams.append('badge_printed', badgePrintedFilter.value);
            }

            window.location.href = url.toString();

        }

        function exportSelectedDelegates() {
            const selectedCheckboxes = document.querySelectorAll('.delegate-checkbox:checked');
            const selectedDelegateIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.getAttribute(
                'data-delegate-id'));

            if (selectedDelegateIds.length === 0) {
                toastr.warning('{{ __db('please_select_at_least_one_delegate') }}');
                return;
            }

            let exportRoute = '{{ route('delegates.exportBadgePrintedDelegates') }}';
            const badgePrintedFilter = document.querySelector('select[name="badge_printed"]');
            if (badgePrintedFilter && badgePrintedFilter.value === '0') {
                exportRoute = '{{ route('delegates.exportNonBadgePrintedDelegates') }}';
            }

            const url = new URL(exportRoute, window.location.origin);

            selectedDelegateIds.forEach(id => {
                url.searchParams.append('delegate_ids[]', id);
            });

            const badgePrintedFilterValue = document.querySelector('select[name="badge_printed"]');
            if (badgePrintedFilterValue) {
                url.searchParams.append('badge_printed', badgePrintedFilterValue.value);
            }

            window.location.href = url.toString();

            setTimeout(() => {
                window.location.reload();
            }, 5000);
        }
    </script>

    <script>
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
        });
    </script>
@endsection
