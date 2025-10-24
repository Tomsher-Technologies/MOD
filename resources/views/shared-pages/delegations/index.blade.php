<div>
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('all_delegations') }}</h2>

        @if (!request('assignment_mode'))
            @directCanany(['import_delegations'])
                <a href="{{ route('delegations.import.form') }}"
                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12" type="button">
                    {{ __db('import') . ' ' . __db('delegations') }}
                </a>
            @enddirectCanany
        @endif
    </div>

    @if (request('driver_id') && request('assignment_mode') === 'driver')
        @php
            $driver = \App\Models\Driver::find(request('driver_id'));
        @endphp
        @if ($driver)
            <div class="mb-4 p-4 bg-[#E6D7A2] rounded-lg">
                <h3 class="font-semibold text-lg">{{ __db('assigning_driver_to_delegation') }}</h3>
                <div class="mt-2 pt-2">
                    <p class="text-sm"><strong>{{ __db('driver') . ' ' . __db('code') }}:</strong> {{ $driver->code }}
                    </p>
                    <p class="text-sm"><strong>{{ __db('name') }}:</strong> {{ $driver->getTranslation('name') }}</p>
                    <p class="text-sm"><strong>{{ __db('military_number') }}:</strong> {{ $driver->military_number }}
                    </p>
                </div>
            </div>
        @endif
    @elseif (request('escort_id') && request('assignment_mode') === 'escort')
        @php
            $escort = \App\Models\Escort::find(request('escort_id'));
        @endphp
        @if ($escort)
            <div class="mb-4 p-4 bg-[#E6D7A2] rounded-lg">
                <h3 class="font-semibold text-lg">{{ __db('assigning_escort_to_delegation') }}</h3>
                <div class="mt-2 pt-2">
                    <p class="text-sm"><strong>{{ __db('escort') . ' ' . __db('code') }}:</strong> {{ $escort->code }}
                    </p>
                    <p class="text-sm"><strong>{{ __db('name') }}:</strong> {{ $escort->getTranslation('name') }}</p>
                    <p class="text-sm"><strong>{{ __db('military_number') }}:</strong> {{ $escort->military_number }}
                    </p>
                </div>
            </div>
        @endif
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <div class=" mb-4 flex items-center justify-between gap-3">
                    <form class="w-[50%] me-4" action="{{ route('delegations.index') }}" method="GET">
                        @if (request('driver_id'))
                            <input type="hidden" name="driver_id" value="{{ request('driver_id') }}">
                        @endif
                        @if (request('escort_id'))
                            <input type="hidden" name="escort_id" value="{{ request('escort_id') }}">
                        @endif
                        @if (request('assignment_mode'))
                            <input type="hidden" name="assignment_mode" value="{{ request('assignment_mode') }}">
                        @endif

                        @foreach (request()->except(['search', 'page', 'driver_id', 'escort_id', 'assignment_mode']) as $k => $v)
                            @if (is_array($v))
                                @foreach ($v as $vv)
                                    <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach

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

                            <a href="{{ route('delegations.index', ['driver_id' => request('driver_id'), 'escort_id' => request('escort_id'), 'assignment_mode' => request('assignment_mode')]) }}"
                                class="absolute end-[85px] bottom-[3px] border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">
                                {{ __db('reset') }}</a>
                        </div>
                    </form>

                    <div class="text-center flex items-center gap-2">

                        @directCanany(['export_delegations_escort'])
                            <form method="POST" id="bulkExportForm" action="{{ route('delegations.bulk-exportPdf') }}">
                                @csrf
                                <input type="hidden" name="export_pdf" id="export_pdf" value="[]">
                                @if (!request('assignment_mode'))
                                    <button
                                        class="text-white flex items-center gap-1 !bg-[#B68A35] hover:bg-[#A87C27] focus:ring-4 focus:ring-yellow-300 font-sm rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                                        type="submit">
                                        <svg class="w-6 h-6 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                                                d="M15 5v14M9 5v14M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                                        </svg>
                                        <span>{{ __db('export_pdf') }}</span>
                                    </button>
                                @enddirectCanany

                        </form>
                        @endif

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
                    $rowClass = function ($delegation) {
                        if (isEscort()) {
                            $hasEscortAssignments = $delegation->escorts->where('pivot.status', 1)->isNotEmpty();

                            if ($hasEscortAssignments) {
                                return 'bg-green-100';
                            } else {
                                return '';
                            }
                        } elseif (isDriver()) {
                            $hasDriverAssignments = $delegation->drivers->where('pivot.status', 1)->isNotEmpty();

                            if ($hasDriverAssignments) {
                                return 'bg-green-100';
                            } else {
                                return '';
                            }
                        } elseif (isHotel()) {
                            $hasRoomAssignments =
                                $delegation->delegates->where('accommodation', 1)->isNotEmpty() ||
                                $delegation->escorts->where('pivot.status', 1)->isNotEmpty() ||
                                $delegation->drivers->where('pivot.status', 1)->isNotEmpty();

                            if ($hasRoomAssignments) {
                                return 'bg-green-100';
                            } else {
                                return '';
                            }
                        }

                        return '';
                    };

                    $columns = [
                        [
                            'label' => '<input type="checkbox" id="selectAllCheckbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            ',
                            'permission' => ['export_delegations_escort'],
                            'render' => function ($row) {
                                $isAssignmentMode = request('assignment_mode');
                                if ($isAssignmentMode) {
                                    return '
                                    <input type="radio" 
                                        name="delegation_selection"
                                        data-delegation-id="' .
                                        $row->id .
                                        '
                                        " class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 delegation-radio">
                                ';
                                } else {
                                    return '
                                    <input type="checkbox" 
                                        data-delegation-id="' .
                                        $row->id .
                                        '
                                        " class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 delegation-checkbox">
                                ';
                                }
                            },
                        ],
                        [
                            'label' => __db('sl_no'),
                            'key' => 'sl_no',
                            'render' => fn($row, $key) => $key +
                                1 +
                                ($delegations->currentPage() - 1) * $delegations->perPage(),
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
                            'label' => __db('invitation_from'),
                            'key' => 'invitation_from',
                            'render' => fn($delegation) => e($delegation->invitationFrom->value ?? '-'),
                        ],
                        [
                            'label' => __db('team_head'),
                            'key' => 'team_head',
                            'render' => function ($delegation) {
                                $teamHeads = $delegation->delegates->filter(fn($d) => $d->team_head);
                                return $teamHeads->isNotEmpty()
                                    ? $teamHeads
                                        ->map(
                                            fn($head) => e(
                                                getLangTitleSeperator(
                                                    $head->getTranslation('title'),
                                                    $head->getTranslation('name'),
                                                ),
                                            ),
                                        )
                                        ->implode('<br>')
                                    : '-';
                            },
                        ],
                        [
                            'label' => __db('designation'),
                            'key' => 'team_head_designation',
                            'render' => function ($delegation) {
                                $teamHeads = $delegation->delegates->filter(fn($d) => $d->team_head);
                                return $teamHeads->isNotEmpty()
                                    ? $teamHeads
                                        ->map(fn($head) => e($head->getTranslation('designation')))
                                        ->implode('<br>')
                                    : '-';
                            },
                        ],
                        [
                            'label' => __db('delegation'),
                            'key' => 'id',
                            'render' => fn($delegation) => e($delegation->code),
                        ],

                        [
                            'label' => __db('escorts'),
                            'key' => 'escorts',
                            'render' => function ($delegation) {
                                if ($delegation->escorts->isEmpty()) {
                                    return '-';
                                }

                                // return $delegation->escorts
                                //     ->map(function ($escort) {
                                //         $searchUrl = route('escorts.index', ['search' => $escort->code]);
                                //         return '<a href="' .
                                //             $searchUrl .
                                //             '" class="text-[#B68A35] hover:underline">' .
                                //             e($escort->code) .
                                //             '</a>';
                                //     })
                                //     ->implode('<br>');

                                return $delegation->escorts
                                    ->map(function ($escort) {
                                        $searchUrl = route('escorts.index', ['search' => $escort->code]);
                                        return '<span class="">' . e($escort->code) . '</span>';
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

                                // return $delegation->drivers
                                //     ->map(function ($driver) {
                                //         $searchUrl = route('drivers.index', ['search' => $driver->code]);
                                //         return '<a href="' .
                                //             $searchUrl .
                                //             '" class="text-[#B68A35] hover:underline">' .
                                //             e($driver->code) .
                                //             '</a>';
                                //     })
                                //     ->implode('<br>');

                                return $delegation->drivers
                                    ->map(function ($driver) {
                                        $searchUrl = route('drivers.index', ['search' => $driver->code]);
                                        return '<span class="">' . e($driver->code) . '</span>';
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
                            'label' => __db('actions'),
                            'key' => __db('actions'),
                            'permission' => [
                                'edit_delegations',
                                'delegate_edit_delegations',
                                'view_delegations',
                                'delegate_view_delegations',
                                'escort_view_delegations',
                                'driver_view_delegations',
                                'hotel_view_delegations',
                            ],
                            'render' => function ($delegation) {
                                $assignmentMode = request('assignment_mode');
                                $buttons = '<div class="flex items-center">';

                                if ($assignmentMode) {
                                    $driverId = request('driver_id');
                                    $escortId = request('escort_id');

                                    if ($assignmentMode === 'driver' && $driverId) {
                                        $buttons .=
                                            '<button type="button"
                                                class="assignBtn  flex items-center gap-2 px-3 py-1 rounded-lg !bg-[#B68A35] !text-white text-sm"
                                                data-assignment-mode="driver"
                                                data-driver-id="' .
                                            $driverId .
                                            '"
                                                data-delegation-id="' .
                                            $delegation->id .
                                            '"
                                                title="' .
                                            __db('assign') .
                                            '">
                                                 <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                </svg>
                                                 <span>' .
                                            __db('assign') .
                                            '</span>
                                            </button>';
                                    } elseif ($assignmentMode === 'escort' && $escortId) {
                                        $buttons .=
                                            '<button type="button" 
                                               class="assignBtn  flex items-center gap-2 px-3 py-1 rounded-lg !bg-[#B68A35] !text-white text-sm"
                                                data-assignment-mode="escort"
                                                data-escort-id="' .
                                            $escortId .
                                            '"
                                                data-delegation-id="' .
                                            $delegation->id .
                                            '"
                                                title="' .
                                            __db('assign') .
                                            '">
                                                 <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                </svg>
                                                 <span>' .
                                            __db('assign') .
                                            '</span>
                                            </button>';
                                    }
                                } else {
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
                                            route('delegations.show', $delegation->id) .
                                            '" class="w-8 h-8  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                            <svg xmlns=\'http://www.w3.org/2000/svg\' width=\'18\' height=\'18\' viewBox=\'0 0 16 12\' fill=\'none\'><path d=\'M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z\' stroke=\'#B68A35\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\' /><path d=\'M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z\' stroke=\'#B68A35\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'></path></svg>
                                        </a>';
                                    }

                                    if (can(['edit_delegations', 'delegate_edit_delegations'])) {
                                        $buttons .=
                                            '<a href="' .
                                            route('delegations.edit', $delegation->id) .
                                            '" title="' .
                                            __db('edit') .
                                            '"
                                            class="w-8 h-8  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                            <svg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 512 512\'><path d=\'M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z\' fill=\'#B68A35\'></path></svg>
                                        </a>';
                                    }

                                    if (can(['delete_delegations', 'delegate_delete_delegations'])) {
                                        $buttons .=
                                            '<button type="button" title="' .
                                            __db('delete') .
                                            '" class="w-8 h-8 text-red-600 dark:text-red-400 rounded-full inline-flex items-center justify-center delete-delegation-btn" data-delegation-id="' .
                                            $delegation->id .
                                            '" data-delegation-code="' .
                                            $delegation->code .
                                            '">
                                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                            </svg>
                                        </button>';
                                    }

                                    if (can(['export_delegations_escort'])) {
                                        $buttons .=
                                            '<a href="' .
                                            route('delegations.exportPdf', ['id' => base64_encode($delegation->id)]) .
                                            '" class
                                        "w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center" style="margin-top:6px;" title="' .
                                            __db('export_pdf') .
                                            '">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7C5E24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <line x1="10" y1="9" x2="9" y2="9"></line>
                                            </svg>
                                        </a>';
                                    }
                                }

                                $buttons .= '</div>';
                                return $buttons;
                            },
                        ],
                    ];
                @endphp

                <x-reusable-table :data="$delegations" :enableRowLimit="true" table-id="delegationsTable" :enableColumnListBtn="true"
                    :columns="$columns" :no-data-message="__db('no_data_found')" :row-class="$rowClass" />

                @if (isEscort() || isDriver() || isHotel())
                    <div class="mt-3 flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2">
                            <div class="h-5 w-5 bg-green-100 rounded"></div>
                            <span class="text-gray-800 text-sm">
                                @if (isEscort())
                                    {{ __db('has_escort_assignments') }}
                                @elseif (isDriver())
                                    {{ __db('has_driver_assignments') }}
                                @elseif (isHotel())
                                    {{ __db('has_room_assignments') }}
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-5 w-5 bg-white border rounded"></div>
                            <span class="text-gray-800 text-sm">
                                @if (isEscort())
                                    {{ __db('no_escort_assignments') }}
                                @elseif (isDriver())
                                    {{ __db('no_driver_assignments') }}
                                @elseif (isHotel())
                                    {{ __db('no_room_assignments') }}
                                @endif
                            </span>
                        </div>
                    </div>
                @endif

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

        <form action="{{ route('delegations.index') }}" method="GET">
            @if (request('driver_id'))
                <input type="hidden" name="driver_id" value="{{ request('driver_id') }}">
            @endif
            @if (request('escort_id'))
                <input type="hidden" name="escort_id" value="{{ request('escort_id') }}">
            @endif
            @if (request('assignment_mode'))
                <input type="hidden" name="assignment_mode" value="{{ request('assignment_mode') }}">
            @endif

            @foreach (request()->except(['invitation_from', 'continent_id', 'country_id', 'invitation_status_id', 'participation_status_id', 'drivers_assigned_status', 'escorts_assigned_status', 'page', 'driver_id', 'escort_id', 'assignment_mode']) as $k => $v)
                @if (is_array($v))
                    @foreach ($v as $vv)
                        <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endif
            @endforeach

            <div class="flex flex-col gap-2 mt-2">

                <div class="flex flex-col">
                    <label class="form-label block mb-1 text-gray-700 font-bold">{{ __db('invitation_from') }}</label>
                    <select name="invitation_from[]" multiple data-placeholder="{{ __db('select') }}"
                        class="select2 w-full rounded-lg border border-gray-300 text-sm">
                        @foreach (getDropDown('departments')->options as $option)
                            <option value="{{ $option->id }}" @if (in_array($option->id, request('invitation_from', []))) selected @endif>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="form-label block text-gray-700 font-bold">{{ __db('all_continents') }}</label>
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
                    <label class="form-label block text-gray-700 font-bold">{{ __db('all_countries') }}</label>
                    <select name="country_id[]" id="country-select" multiple data-placeholder="{{ __db('select') }}"
                        class="select2 w-full rounded-lg border border-gray-300 text-sm">
                        {{-- @foreach (getAllCountries() as $option)
                    <option value="{{ $option->id }}"
                        {{ is_array(request('country_id')) && in_array($option->id, request('country_id')) ? 'selected' : '' }}>
                        {{ $option->name }}
                    </option>
                @endforeach --}}
                    </select>
                </div>

                <div class="flex flex-col">
                    <label
                        class="form-label block text-gray-700 font-bold">{{ __db('all_invitation_statuses') }}</label>
                    <select multiple name="invitation_status_id[]" data-placeholder="{{ __db('select') }}"
                        class="select2 w-full rounded-lg border border-gray-300 text-sm">
                        @foreach (getDropDown('invitation_status')->options as $status)
                            <option value="{{ $status->id }}"
                                {{ is_array(request('invitation_status_id')) && in_array($status->id, request('invitation_status_id')) ? 'selected' : '' }}>
                                {{ $status->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label
                        class="form-label block text-gray-700 font-bold">{{ __db('all_participation_statuses') }}</label>
                    <select multiple name="participation_status_id[]" data-placeholder="{{ __db('select') }}"
                        class="select2 w-full rounded-lg border border-gray-300 text-sm">
                        @foreach (getDropDown('participation_status')->options as $status)
                            <option value="{{ $status->id }}"
                                {{ is_array(request('participation_status_id')) && in_array($status->id, request('participation_status_id')) ? 'selected' : '' }}>
                                {{ $status->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if (isDriver())
                    <div class="flex flex-col">
                        <label
                            class="form-label block text-gray-700 font-bold">{{ __db('drivers_assigned_status') }}</label>
                        <select name="drivers_assigned_status" data-placeholder="{{ __db('select') }}"
                            class="select2 w-full rounded-lg border border-gray-300 text-sm">
                            <option value="">{{ __db('all') }}</option>
                            <option value="not_assigned" @if (request('drivers_assigned_status') === 'not_assigned') selected @endif>
                                {{ __db('not_assigned') }}
                            </option>
                            <option value="assigned" @if (request('drivers_assigned_status') === 'assigned') selected @endif>
                                {{ __db('assigned') }}
                            </option>
                        </select>
                    </div>
                @endif

                @if (isEscort())
                    <div class="flex flex-col">
                        <label
                            class="form-label block text-gray-700 font-bold">{{ __db('escorts_assigned_status') }}</label>
                        <select name="escorts_assigned_status" data-placeholder="{{ __db('select') }}"
                            class="select2 w-full rounded-lg border border-gray-300 text-sm">
                            <option value="">{{ __db('all') }}</option>
                            <option value="not_assigned" @if (request('escorts_assigned_status') === 'not_assigned') selected @endif>
                                {{ __db('not_assigned') }}
                            </option>
                            <option value="assigned" @if (request('escorts_assigned_status') === 'assigned') selected @endif>
                                {{ __db('assigned') }}
                            </option>
                        </select>
                    </div>
                @endif


            </div>

            <div class="grid grid-cols-2 gap-4 mt-6">
                <a href="{{ route('delegations.index', ['driver_id' => request('driver_id'), 'escort_id' => request('escort_id'), 'assignment_mode' => request('assignment_mode')]) }}"
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

            const exportInput = document.getElementById('export_pdf');
            const bulkActions = document.getElementById('bulk-actions');

            function updateExportInput() {
                let selected;
                if (document.querySelector('.delegation-radio')) {
                    const selectedRadio = document.querySelector('.delegation-radio:checked');
                    selected = selectedRadio ? [selectedRadio.dataset.delegationId] : [];
                    if (selected.length > 0) {
                        bulkActions.classList.remove('hidden');
                    } else {
                        bulkActions.classList.add('hidden');
                    }
                } else {
                    selected = Array.from(document.querySelectorAll('.delegation-checkbox:checked'))
                        .map(cb => cb.dataset.delegationId);
                    exportInput.value = JSON.stringify(selected);

                    if (selected.length > 0) {
                        bulkActions.classList.remove('hidden');
                    } else {
                        bulkActions.classList.add('hidden');
                    }
                }
            }

            document.getElementById('selectAllCheckbox').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.delegation-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateExportInput();
            });

            document.querySelectorAll('.delegation-checkbox').forEach(cb => {
                cb.addEventListener('change', updateExportInput);
            });

            document.querySelectorAll('.delegation-radio').forEach(radio => {
                radio.addEventListener('change', updateExportInput);
            });

            document.getElementById('bulkExportForm').addEventListener('submit', function(e) {
                let selected;
                if (document.querySelector('.delegation-radio')) {
                    const selectedRadio = document.querySelector('.delegation-radio:checked');
                    selected = selectedRadio ? [selectedRadio.dataset.delegationId] : [];
                } else {
                    const exportInput = document.getElementById('export_pdf');
                    selected = JSON.parse(exportInput.value || '[]');
                }

                if (selected.length === 0) {
                    e.preventDefault();
                    toastr.error("{{ __db('select_at_least_one_delegation') }}");
                }
            });

            document.querySelectorAll('.assignBtn').forEach(button => {
                button.addEventListener('click', function() {

                    Swal.fire({
                        title: '{{ __db('confirm_assignment') }}',
                        text: '{{ __db('are_you_sure_assign') }}',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: '{{ __db('yes_assign') }}',
                        cancelButtonText: '{{ __db('cancel') }}',
                        customClass: {
                            confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-center text-white bg-[#B68A35] hover:bg-[#A87C27]',
                        },
                    }).then((confirmResult) => {
                        if (confirmResult.isConfirmed) {


                            const urlParams = new URLSearchParams(window.location.search);
                            let assignmentUrl = '';
                            let assignmentTitle = '';

                            if (urlParams.get('driver_id') && urlParams.get(
                                    'assignment_mode') ===
                                'driver') {
                                const driverId = urlParams.get('driver_id');
                                assignmentUrl = `/mod-events/drivers/${driverId}/assign`;
                                assignmentTitle =
                                    "{{ __db('assign_driver_to_delegation') }}";
                            } else if (urlParams.get('escort_id') && urlParams.get(
                                    'assignment_mode') ===
                                'escort') {
                                const escortId = urlParams.get('escort_id');
                                assignmentUrl = `/mod-events/escorts/${escortId}/assign`;
                                assignmentTitle =
                                    "{{ __db('assign_escort_to_delegation') }}";
                            } else {
                                toastr.error("{{ __db('invalid_assignment_mode') }}");
                                return;
                            }

                            if (assignmentUrl) {
                                const urlParams = new URLSearchParams(window.location
                                    .search);
                                const isAssignmentMode = urlParams.get('assignment_mode');
                                const isDriverAssignment = urlParams.get('driver_id') &&
                                    isAssignmentMode ===
                                    'driver';
                                const delegationId = this.getAttribute(
                                    'data-delegation-id');

                                if (isDriverAssignment) {
                                    const checkFormData = new FormData();
                                    checkFormData.append('_token', '{{ csrf_token() }}');
                                    checkFormData.append('delegation_id', delegationId);
                                    checkFormData.append('assignment_mode',
                                        isAssignmentMode);

                                    fetch(assignmentUrl, {
                                            method: 'POST',
                                            body: checkFormData,
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest',
                                            }
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.status === 'success') {
                                                if (data.message) {
                                                    toastr.success(data.message);
                                                } else {
                                                    toastr.success(
                                                        "{{ __db('updated_successfully') }}"
                                                    );
                                                }
                                                setTimeout(() => {
                                                    window.location.href = data
                                                        .redirect_url;
                                                }, 1000);
                                            } else if (data.status === 'error' && data
                                                ?.code == '3') {
                                                Swal.fire({
                                                    title: '{{ __db('driver_already_has_assignment') }}',
                                                    text: '{{ __db('reassign_or_replace_assignment') }}',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    showDenyButton: true,
                                                    confirmButtonText: '{{ __db('reassign') }}',
                                                    denyButtonText: '{{ __db('replace') }}',
                                                    cancelButtonText: '{{ __db('cancel') }}',
                                                    customClass: {
                                                        confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-center text-white bg-[#B68A35] hover:bg-[#A87C27]',
                                                    },
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        Swal.fire({
                                                            title: '{{ __db('start_date_for_reassignment') }}',
                                                            input: 'date',
                                                            inputLabel: '{{ __db('start_date') }}',
                                                            inputPlaceholder: '{{ __db('select_date') }}',
                                                            inputValidator: (
                                                                value
                                                            ) => {
                                                                if (!
                                                                    value
                                                                )
                                                                    return '{{ __db('start_date_required') }}';
                                                            },
                                                            showCancelButton: true,
                                                            confirmButtonText: '{{ __db('submit') }}',
                                                            cancelButtonText: '{{ __db('cancel') }}',
                                                            customClass: {
                                                                confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-center text-white bg-[#B68A35] hover:bg-[#A87C27]',
                                                            },
                                                        }).then((
                                                                dateResult
                                                                ) => {
                                                                if (dateResult
                                                                    .isConfirmed
                                                                ) {
                                                                    const
                                                                        reassignFormData =
                                                                        new FormData();
                                                                    reassignFormData
                                                                        .append(
                                                                            '_token',
                                                                            '{{ csrf_token() }}'
                                                                        );
                                                                    reassignFormData
                                                                        .append(
                                                                            'delegation_id',
                                                                            this
                                                                            .getAttribute(
                                                                                'data-delegation-id'
                                                                            )
                                                                        );
                                                                    reassignFormData
                                                                        .append(
                                                                            'assignment_mode',
                                                                            isAssignmentMode
                                                                        );
                                                                    reassignFormData
                                                                        .append(
                                                                            'action',
                                                                            'reassign'
                                                                        );
                                                                    reassignFormData
                                                                        .append(
                                                                            'start_date',
                                                                            dateResult
                                                                            .value
                                                                        );

                                                                    fetch(assignmentUrl, {
                                                                            method: 'POST',
                                                                            body: reassignFormData,
                                                                            headers: {
                                                                                'X-Requested-With': 'XMLHttpRequest',
                                                                            }
                                                                        })
                                                                        .then(
                                                                            response =>
                                                                            response
                                                                            .json()
                                                                        )
                                                                        .then(
                                                                            reassignData => {
                                                                                if (reassignData
                                                                                    .status ===
                                                                                    'success'
                                                                                ) {
                                                                                    if (reassignData
                                                                                        .message
                                                                                    ) {
                                                                                        toastr
                                                                                            .success(
                                                                                                reassignData
                                                                                                .message
                                                                                            );
                                                                                    } else {
                                                                                        toastr
                                                                                            .success(
                                                                                                "{{ __db('updated_successfully') }}"
                                                                                            );
                                                                                    }
                                                                                    setTimeout
                                                                                        (
                                                                                            () => {
                                                                                                window
                                                                                                    .location
                                                                                                    .href =
                                                                                                    reassignData
                                                                                                    .redirect_url;
                                                                                            },
                                                                                            1000
                                                                                        );
                                                                                } else {
                                                                                    if (reassignData
                                                                                        .message
                                                                                    ) {
                                                                                        toastr
                                                                                            .error(
                                                                                                reassignData
                                                                                                .message ||
                                                                                                "{{ __db('failed_to_update') }}"
                                                                                            );
                                                                                    } else {
                                                                                        toastr
                                                                                            .error(
                                                                                                "{{ __db('failed_to_update') }}"
                                                                                            );
                                                                                    }
                                                                                }
                                                                            }
                                                                        )
                                                                        .catch(
                                                                            error => {
                                                                                console
                                                                                    .error(
                                                                                        'Reassign error:',
                                                                                        error
                                                                                    );
                                                                                toastr
                                                                                    .error(
                                                                                        "{{ __db('failed_to_update') }}"
                                                                                    );
                                                                            }
                                                                        );
                                                                }
                                                            });
                                                    } else if (result
                                                        .isDenied) {
                                                        const replaceFormData =
                                                            new FormData();
                                                        replaceFormData.append(
                                                            '_token',
                                                            '{{ csrf_token() }}'
                                                        );
                                                        replaceFormData.append(
                                                            'delegation_id',
                                                            delegationId);
                                                        replaceFormData.append(
                                                            'assignment_mode',
                                                            isAssignmentMode
                                                        );
                                                        replaceFormData.append(
                                                            'action',
                                                            'replace');

                                                        fetch(assignmentUrl, {
                                                                method: 'POST',
                                                                body: replaceFormData,
                                                                headers: {
                                                                    'X-Requested-With': 'XMLHttpRequest',
                                                                }
                                                            })
                                                            .then(response =>
                                                                response.json())
                                                            .then(
                                                                replaceData => {
                                                                    if (replaceData
                                                                        .status ===
                                                                        'success'
                                                                    ) {
                                                                        if (replaceData
                                                                            .message
                                                                        ) {
                                                                            toastr
                                                                                .success(
                                                                                    replaceData
                                                                                    .message
                                                                                );
                                                                        } else {
                                                                            toastr
                                                                                .success(
                                                                                    "{{ __db('updated_successfully') }}"
                                                                                );
                                                                        }
                                                                        setTimeout
                                                                            (() => {
                                                                                    window
                                                                                        .location
                                                                                        .href =
                                                                                        replaceData
                                                                                        .redirect_url;
                                                                                },
                                                                                1000
                                                                            );
                                                                    } else {
                                                                        if (replaceData
                                                                            .message
                                                                        ) {
                                                                            toastr
                                                                                .error(
                                                                                    replaceData
                                                                                    .message ||
                                                                                    "{{ __db('failed_to_update') }}"
                                                                                );
                                                                        } else {
                                                                            toastr
                                                                                .error(
                                                                                    "{{ __db('failed_to_update') }}"
                                                                                );
                                                                        }
                                                                    }
                                                                })
                                                            .catch(error => {
                                                                console
                                                                    .error(
                                                                        'Replace error:',
                                                                        error
                                                                    );
                                                                toastr
                                                                    .error(
                                                                        "{{ __db('failed_to_update') }}"
                                                                    );
                                                            });
                                                    }
                                                });
                                            } else {
                                                if (data.message) {
                                                    toastr.error(data.message ||
                                                        "{{ __db('failed_to_update') }}"
                                                    );
                                                } else {
                                                    toastr.error(
                                                        "{{ __db('failed_to_update') }}"
                                                    );
                                                }
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Assignment error:', error);
                                            toastr.error(
                                                "{{ __db('failed_to_update') }}");
                                        });
                                } else {
                                    const formData = new FormData();
                                    formData.append('_token', '{{ csrf_token() }}');

                                    if (isAssignmentMode) {
                                        formData.append('delegation_id', delegationId);
                                        formData.append('assignment_mode',
                                            isAssignmentMode);
                                    } else {
                                        selected.forEach(id => {
                                            formData.append('delegation_id[]', id);
                                        });
                                    }

                                    fetch(assignmentUrl, {
                                            method: 'POST',
                                            body: formData,
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest',
                                            }
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.status === 'success') {
                                                if (data.message) {
                                                    toastr.success(data.message);
                                                } else {
                                                    toastr.success(
                                                        "{{ __db('updated_successfully') }}"
                                                    );
                                                }
                                                setTimeout(() => {
                                                    window.location.href = data
                                                        .redirect_url;
                                                }, 1000);
                                            } else {
                                                if (data.message) {
                                                    toastr.error(data.message ||
                                                        "{{ __db('failed_to_update') }}"
                                                    );
                                                } else {
                                                    toastr.error(
                                                        "{{ __db('failed_to_update') }}"
                                                    );
                                                }
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Assignment error:', error);
                                            toastr.error(
                                                "{{ __db('failed_to_update') }}");
                                        });
                                }
                            }
                        }
                    });
                })
            })

            const noteModalContent = document.getElementById('note-modal-content');

            document.querySelectorAll('.note-icon').forEach(icon => {
                icon.addEventListener('click', function() {
                    const note1 = this.getAttribute('data-note1') || '';
                    const note2 = this.getAttribute('data-note2') || '';

                    let html = '';


                    if (note2.trim() !== '') {
                        html += `
                    <h3 class="mb-2 font-medium">{{ __db('note_2') }}:</h3>
                    <div class="border p-5 rounded-lg">
                        <p>${note2}</p>
                    </div>
                `;
                    }

                    if (html === '') {
                        html = '<p>{{ __db('no_notes_available') }}</p>';
                    }

                    noteModalContent.innerHTML = html;
                });
            });

            document.querySelectorAll('.delete-delegation-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const delegationId = this.getAttribute('data-delegation-id');
                    const delegationCode = this.getAttribute('data-delegation-code');

                    const deletionMessage =
                        "{{ __db('delete_delegation_confirm_msg') }}";

                    const finalDeletionMessage = deletionMessage.replace(
                        'delegation_id',
                        delegationCode);

                    Swal.fire({
                        title: '{{ __db('are_you_sure') }}',
                        text: finalDeletionMessage + "?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: '{{ __db('yes_delete') }}',
                        cancelButtonText: '{{ __db('cancel') }}',
                        customClass: {
                            popup: 'w-full max-w-2xl',
                            confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-red rounded-lg hover:bg-ref',
                            cancelButton: 'px-4 rounded-lg'
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action =
                                '{{ url('/mod-events/delegations') }}/' +
                                delegationId;

                            const tokenInput = document.createElement('input');
                            tokenInput.type = 'hidden';
                            tokenInput.name = '_token';
                            tokenInput.value = '{{ csrf_token() }}';
                            form.appendChild(tokenInput);

                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(methodInput);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
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
