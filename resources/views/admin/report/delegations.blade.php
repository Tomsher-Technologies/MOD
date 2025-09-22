@extends('layouts.admin_account', ['title' => __db('all_delegations')])

@section('content')
    <div>
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('all_delegations') }}</h2>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <div class=" mb-4 flex items-center justify-between gap-3">
                    <form class="w-[50%] me-4" action="{{ route('reports-delegations') }}" method="GET">
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="default-search" name="search" value="{{ request('search') }}"
                                class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg "
                                placeholder="{{ __db('delegation_search_placeholder') }}" />
                            <button type="submit"
                                class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>
                        </div>
                    </form>

                    <div class="text-center">
                        <button
                            class="text-white flex items-center gap-1 !bg-[#B68A35] hover:bg-[#A87C27] focus:ring-4 focus:ring-yellow-300 font-sm rounded-lg text-sm px-5 py-2.5 focus:outline-none"
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
                </div>

                @php
                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'key' => 'sl_no',
                            'render' => fn($row, $key) => $key +
                                1 +
                                ($delegations->currentPage() - 1) * $delegations->perPage(),
                        ],
                        [
                            'label' => __db('delegation'),
                            'key' => 'id',
                            'render' => fn($delegation) => e($delegation->code),
                        ],
                        [
                            'label' => __db('continent'),
                            'key' => 'continent',
                            'render' => fn($delegation) => e($delegation->continent->value ?? '-'),
                        ],
                        [
                            'label' => __db('country'),
                            'key' => 'country',
                            'render' => function ($delegation) {
                                if (!$delegation->country) {
                                    return '-';
                                }

                                $flag = $delegation->country->flag
                                    ? '<img src="' .
                                        getUploadedImage($delegation->country->flag) .
                                        '" 
                                        alt="' .
                                        e($delegation->country->name) .
                                        ' flag" 
                                        class="inline-block w-6 h-4 mr-2 rounded-sm object-cover" />'
                                    : '';

                                return $flag . ' ' . e($delegation->country->name);
                            },
                        ],
                        [
                            'label' => __db('team_head'),
                            'key' => 'team_head',
                            'render' => function ($delegation) {
                                $teamHeads = $delegation->delegates->filter(fn($d) => $d->team_head);
                                return $teamHeads->isNotEmpty()
                                    ? $teamHeads->map(fn($head) => e($head->getTranslation('title') . ". " . $head->getTranslation('name')))->implode('<br>')
                                    : '-';
                            },
                        ],
                        
                        [
                            'label' => __db('invitation_from'),
                            'key' => 'invitation_from',
                            'render' => fn($delegation) => e($delegation->invitationFrom->value ?? '-'),
                        ],

                        [
                            'label' => __db('escorts'),
                            'key' => 'escorts',
                            'render' => function ($delegation) {
                                if ($delegation->escorts->isEmpty()) {
                                    return '-';
                                }

                                return $delegation->escorts
                                    ->map(function ($escort) {
                                        $searchUrl = route('escorts.index', ['search' => $escort->code]);
                                        return '<a href="' .
                                            $searchUrl .
                                            '" class="text-[#B68A35] hover:underline">' .
                                            e($escort->code) .
                                            '</a>';
                                    })
                                    ->implode('<br>');
                            },
                        ],
                        [
                            'label' => __db('drivers'),
                            'key' => 'drivers',
                            'render' => function ($delegation) {
                                if ($delegation->drivers->isEmpty()) {
                                    return '-';
                                }

                                return $delegation->drivers
                                    ->map(function ($driver) {
                                        $searchUrl = route('drivers.index', ['search' => $driver->code]);
                                        return '<a href="' .
                                            $searchUrl .
                                            '" class="text-[#B68A35] hover:underline">' .
                                            e($driver->code) .
                                            '</a>';
                                    })
                                    ->implode('<br>');
                            },
                        ],
                        [
                            'label' => __db('invitation_status'),
                            'key' => 'invitation_status',
                            'render' => fn($delegation) => e($delegation->invitationStatus->value ?? '-'),
                        ],
                        [
                            'label' => __db('participation_status'),
                            'key' => 'participation_status',
                            'render' => fn($delegation) => e($delegation->participationStatus->value ?? '-'),
                        ],

                        [
                            'key' => 'note',
                            'label' => __db('note'),
                            'render' => function ($d) {
                                if (empty($d->note1) && empty($d->note2)) {
                                    return '-';
                                }
                                return '<svg class="w-6 h-6 text-[#B68A35] cursor-pointer note-icon"
                                    data-modal-target="note-modal" data-modal-toggle="note-modal"
                                    data-note1="' .
                                    e($d->note1) .
                                    '"
                                    data-note2="' .
                                    e($d->note2) .
                                    '"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.556 8.5h8m-8 3.5H12m7.111-7H4.89a.896.896 0 0 0-.629.256.868.868 0 0 0-.26.619v9.25c0 .232.094.455.26.619A.896.896 0 0 0 4.89 16H9l3 4 3-4h4.111a.896.896 0 0 0 .629-.256.868.868 0 0 0 .26-.619v-9.25a.868.868 0 0 0-.26-.619.896.896 0 0 0-.63-.256Z"/>
                                </svg>';
                            },
                        ],
                        [
                            'label' => __db(__db('actions')),
                            'key' => __db('actions'),
                            'render' => function ($delegation) {
                                $buttons = '<div class="flex">';
                            
                                if (
                                    can([
                                        'view_delegations',
                                        'delegate_view_delegations',
                                        'escort_view_delegations',
                                        'driver_view_delegations',
                                        'hotel_view_delegations',
                                    ])
                                ) {
                                    $buttons .=
                                        '<a href="' .
                                        route('reports-delegations.show', base64_encode($delegation->id)) .
                                        '" class="w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center" title="'.__db('view_delegation').'">
                                        <svg xmlns=\'http://www.w3.org/2000/svg\' width=\'18\' height=\'18\' viewBox=\'0 0 16 12\' fill=\'none\'><path d=\'M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z\' stroke=\'#7C5E24\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\' /><path d=\'M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z\' stroke=\'#7C5E24\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'></path></svg>
                                    </a>';
                                }

                                $buttons .='<a href="'.route("delegations.exportPdf", ["id" => base64_encode($delegation->id)]).'" class
                                    "w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center" style="margin-top:6px;" title="'.__db('export_pdf').'">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7C5E24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <line x1="10" y1="9" x2="9" y2="9"></line>
                                        </svg>
                                    </a>';

                                    $buttons .= '</div>';
                                return $buttons;
                            },
                        ],
                    ];
                @endphp

                <x-reusable-table :data="$delegations" :enableRowLimit="true" table-id="delegationsTable" :enableColumnListBtn="true"
                   :columns="$columns" :no-data-message="__db('no_data_found')" />
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

        <form action="{{ route('reports-delegations') }}" method="GET">
            <div class="flex flex-col gap-2 mt-2">

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
                        @foreach (getAllCountries() as $option)
                            <option value="{{ $option->id }}"
                                {{ is_array(request('country_id')) && in_array($option->id, request('country_id')) ? 'selected' : '' }}>
                                {{ $option->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label
                        class="form-label block text-gray-700 font-medium">{{ __db('all_invitation_statuses') }}</label>
                    <select multiple name="invitation_status_id" data-placeholder="{{ __db('select') }}"
                        class="select2 w-full rounded-lg border border-gray-300 text-sm">
                        <option value="">{{ __db('select') }}</option>
                        @foreach (getDropDown('invitation_status')->options as $status)
                            <option value="{{ $status->id }}"
                                {{ request('invitation_status_id') == $status->id ? 'selected' : '' }}>
                                {{ $status->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label
                        class="form-label block text-gray-700 font-medium">{{ __db('all_participation_statuses') }}</label>
                    <select multiple name="participation_status_id" data-placeholder="{{ __db('select') }}"
                        class="select2 w-full rounded-lg border border-gray-300 text-sm">
                        <option value="">{{ __db('select') }}</option>
                        @foreach (getDropDown('participation_status')->options as $status)
                            <option value="{{ $status->id }}"
                                {{ request('participation_status_id') == $status->id ? 'selected' : '' }}>
                                {{ $status->value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-6">
                <a href="{{ route('reports-delegations') }}"
                    class="px-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100">{{ __db('reset') }}</a>
                <button type="submit"
                    class="justify-center inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]">{{ __db('filter') }}</button>
            </div>
        </form>
    </div>

    <div id="note-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow ">
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">{{ __db('note') }}</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 mr-auto inline-flex items-center"
                        data-modal-hide="note-modal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6 text-gray-700 " id="note-modal-content">
                </div>
            </div>
        </div>
    </div>


</div>

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const noteModalContent = document.getElementById('note-modal-content');

            document.querySelectorAll('.note-icon').forEach(icon => {
                icon.addEventListener('click', function() {
                    const note1 = this.getAttribute('data-note1') || '';
                    const note2 = this.getAttribute('data-note2') || '';

                    let html = '';

                    if (note1.trim() !== '') {
                        html += `
                    <h3 class="mb-2 font-medium">Note 1:</h3>
                    <div class="border p-5 rounded-lg">
                        <p>${note1}</p>
                    </div>
                `;
                    }

                    if (note2.trim() !== '') {
                        html += `
                    <h3 class="mb-2 font-medium">Note 2:</h3>
                    <div class="border p-5 rounded-lg">
                        <p>${note2}</p>
                    </div>
                `;
                    }

                    if (html === '') {
                        html = '<p>No notes available.</p>';
                    }

                    noteModalContent.innerHTML = html;
                });
            });

        });
    </script>

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
        });
    </script>
@endsection

@endsection
