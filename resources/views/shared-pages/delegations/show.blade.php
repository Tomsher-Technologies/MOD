@extends('layouts.admin_account', ['title' => __db('delegation_details')])

@section('content')
    @if (!$delegation->canAssignServices() && $delegation?->invitationStatus?->code !== '1')
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <p><strong>{{ __db('Note') }}:</strong> {{ __db('delegation_cannot_assign_services') }}.
                {{ __db('delegation_has_status') }} "{{ $delegation->invitationStatus?->value }}"
                {{ __db('cannot_assign_these_services') }}.</p>
        </div>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-4 mb-10">
        <h2 class="font-semibold text-2xl">{{ __db('delegation') }}</h2>
        <div class="flex gap-3 ms-auto">
            @directCanany(['edit_delegations', 'del_edit_delegations'])
                <a href="{{ route('delegations.edit', $delegation->id) }}" data-modal-hide="default-modal"
                    class="btn text-sm ms-auto !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-5">
                    {{ __db('edit') }}
                </a>
            @enddirectCanany

            <x-back-btn title="" class="" back-url="{{ url()->previous() ?: route('delegations.index') }}" />
        </div>
    </div>

    @php
        $columns = [
            ['label' => __db('delegation_id'), 'render' => fn($row) => $row->code ?? '-'],
            ['label' => __db('invitation_from'), 'render' => fn($row) => $row->invitationFrom->value ?? '-'],
            ['label' => __db('continent'), 'render' => fn($row) => $row->continent->value ?? '-'],
            [
                'label' => __db('country'),
                'key' => 'country',
                'render' => function ($row) {
                    if (!$row->country) {
                        return '-';
                    }

                    $flag = $row->country->flag
                        ? '<img src="' .
                            getUploadedImage($row->country->flag) .
                            '" 
                                        alt="' .
                            e($row->country->name) .
                            ' flag" 
                                        class="inline-block w-6 h-4 mr-2 rounded-sm object-cover" />'
                        : '';

                    return $flag . ' ' . e($row->country->name);
                },
            ],

            ['label' => __db('invitation_status'), 'render' => fn($row) => $row->invitationStatus->value ?? '-'],
            ['label' => __db('participation_status'), 'render' => fn($row) => $row->participationStatus->value ?? '-'],
        ];

        $data = [$delegation];
        $noDataMessage = __db('no_data_found');
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-4">
        <div class="xl:col-span-12">
            <div class="bg-white h-full w-full rounded-lg border-0 p-7">
                <x-reusable-table :columns="$columns" tableId="delegationDetails" :data="$data" :noDataMessage="$noDataMessage" />

                <hr class="my-5">

                @php
                    $note1_columns = [['label' => __db('note_1'), 'render' => fn($row) => $row->note1 ?? '-']];
                    $note2_columns = [['label' => __db('note_2'), 'render' => fn($row) => $row->note2 ?? '-']];
                    $data = [$delegation];
                    $noDataMessage = __db('no_data_found');
                @endphp

                <div class="grid grid-cols-2 gap-6 mt-3">
                    <x-reusable-table :columns="$note1_columns" tableId="not1Table" :data="$data" :noDataMessage="$noDataMessage" />
                    <x-reusable-table :columns="$note2_columns" :data="$data" tableId="note2Table" :noDataMessage="$noDataMessage" />
                </div>
            </div>
        </div>
    </div>


    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('delegates') }} ({{ $delegation->delegates->count() }})</h2>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

                @php
                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'render' => fn($row, $key) => $key + 1,
                        ],
                        [
                            'label' => __db('delegate_code'),
                            'render' => fn($row) => $row->code ?? '-',
                        ],
                        [
                            'label' => __db('name_en'),
                            'render' => function ($row) {
                                $badge = $row->team_head
                                    ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span> '
                                    : '';
                                $name = $row->name_en;
                                $title = $row->title_en;
                                return $badge . '<div class="block">' . e($title . ' ' . $name) . '</div>';
                            },
                        ],
                        [
                            'label' => __db('name_ar'),
                            'render' => function ($row) {
                                $badge = $row->team_head
                                    ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span> '
                                    : '';
                                $name = $row->name_ar;
                                $title = $row->title_ar;
                                return $badge . '<div class="block">' . e($title . ' ' . $name) . '</div>';
                            },
                        ],
                        [
                            'label' => __db('designation'),
                            'render' => fn($row) => $row->getTranslation('designation') ?? '-',
                        ],
                        [
                            'label' => __db('internal_ranking'),
                            'render' => fn($row) => $row->internalRanking->value ?? '-',
                        ],
                        [
                            'label' => __db('gender'),
                            'render' => fn($row) => $row->gender->value ?? '-',
                        ],
                        [
                            'label' => __db('parent_id'),
                            'render' => fn($row) => $row->parent?->getTranslation('name') ?? '-',
                        ],
                        [
                            'label' => __db('relationship'),
                            'render' => fn($row) => $row->relationship->value ?? '-',
                        ],
                        [
                            'label' => __db('badge_printed'),
                            'render' => fn($row) => $row->badge_printed ? 'Yes' : 'No',
                        ],
                        [
                            'label' => __db('participation_status'),
                            'render' => function ($row) {
                                return $row->participation_status ?? '-';
                            },
                        ],
                        [
                            'label' => __db('accommodation'),
                            'render' => function ($row) {
                                if (!$row->accommodation) {
                                    return 'Not Required';
                                }

                                $room = $row->currentRoomAssignment ?? null;

                                $accommodation = $row->current_room_assignment_id
                                    ? $room?->hotel?->hotel_name .
                                            ' - ' .
                                            $room->roomType?->roomType?->value .
                                            ' - ' .
                                            $room?->room_number ??
                                        '-'
                                    : '-';

                                return $accommodation ?? '-';
                            },
                        ],
                        [
                            'label' => __db('arrival_status'),
                            'render' => function ($row) {
                                $id = $row->id ?? uniqid();
                                return '<svg class="cursor-pointer" width="36" height="30" data-modal-target="delegate-transport-modal-' .
                                    $id .
                                    '" data-modal-toggle="delegate-transport-modal-' .
                                    $id .
                                    '" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#B68A35"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><rect width="480" height="32" x="16" y="464" fill="var(--ci-primary-color, #B68A35)" class="ci-primary"></rect><path fill="var(--ci-primary-color, #B68A35)" d="M455.688,152.164c-23.388-6.515-48.252-6.053-70.008,1.3l-.894.3-65.1,30.94L129.705,109.176a47.719,47.719,0,0,0-49.771,8.862L54.5,140.836a24,24,0,0,0,2.145,37.452l117.767,83.458-45.173,23.663L93.464,252.722a48.067,48.067,0,0,0-51.47-8.6l-19.455,8.435a24,24,0,0,0-11.642,33.3L83.718,422.684,480.3,227.21c23.746-11.177,26.641-29.045,21.419-42.059C495.931,170.723,479.151,158.7,455.688,152.164Zm10.9,46.133-.149.07L97.394,380.267l-54.176-101.8,11.5-4.987a16.021,16.021,0,0,1,17.157,2.867l52.336,47.819,111.329-58.318L83.322,157.974l17.971-16.108a15.908,15.908,0,0,1,16.59-2.954l202.943,80.681,75.95-36.095c15.456-5.009,33.863-5.165,50.662-.413,13.834,3.914,21.182,9.6,23.672,12.582A24.211,24.211,0,0,1,466.59,198.3Z" class="ci-primary"></path></g></svg>';
                            },
                        ],
                    ];
                    $data = $delegation->delegates;
                    $noDataMessage = __db('no_delegates_found');
                @endphp

                <x-reusable-table :columns="$columns" table-id="delegatesTable" :enableColumnListBtn="true" :data="$data"
                    :noDataMessage="$noDataMessage" />
            </div>
        </div>
    </div>

    <hr class="mx-6 border-neutral-200 h-10">

    <div class="flex items-center justify-between mt-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('escorts') }} ({{ $delegation->escorts->count() }})</h2>

        @if ($delegation->canAssignServices())
            <div class="flex items-center gap-3">
                @directCanany(['add_escorts', 'escort_add_escorts'])
                    <a href="{{ route('escorts.index', ['delegation_id' => $delegation->id, 'assignment_mode' => 'escort']) }}"
                        class="bg-[#B68A35] text-white px-4 py-2 rounded-lg">{{ __db('add') . ' ' . __db('escorts') }}</a>
                @enddirectCanany
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                @php
                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'render' => fn($row, $key) => $key + 1,
                        ],
                        [
                            'label' => __db('escort') . ' ' . __db('code'),
                            'render' => function ($escort) {
                                $searchUrl = route('escorts.index', ['search' => $escort->code]);
                                return '<a href="' .
                                    $searchUrl .
                                    '" class="text-[#B68A35] hover:underline">' .
                                    e($escort->code) .
                                    '</a>';
                            },
                        ],
                        [
                            'label' => __db('military_number'),
                            'key' => 'military_number',
                            'render' => function ($escort) {
                                $searchUrl = route('escorts.index', ['search' => $escort->military_number]);
                                return '<a href="' .
                                    $searchUrl .
                                    '" class="text-[#B68A35] hover:underline">' .
                                    e($escort->military_number) .
                                    '</a>';
                            },
                        ],
                        [
                            'label' => __db('name'),
                            'key' => 'name',
                            'render' => function ($escort) {
                                $searchUrl = route('escorts.index', ['search' => $escort->name_en]);
                                return '<a href="' .
                                    $searchUrl .
                                    '" class="text-[#B68A35] hover:underline">' .
                                    e($escort->getTranslation('title') . ' ' . $escort->getTranslation('name')) .
                                    '</a>';
                            },
                        ],
                        [
                            'label' => __db('phone_number'),
                            'key' => 'phone_number',
                            'render' => fn($escort) => '<span dir="ltr">' . e($escort->phone_number) . '</span>',
                        ],
                        [
                            'label' => __db('gender'),
                            'key' => 'gender',
                            'render' => fn($escort) => e(optional($escort->gender)->value),
                        ],
                        [
                            'label' => __db('spoken_languages'),
                            'key' => 'known_languages',
                            'render' => function ($escort) {
                                $ids = $escort->spoken_languages ? explode(',', $escort->spoken_languages) : [];
                                $names = \App\Models\DropdownOption::whereIn('id', $ids)->pluck('value')->toArray();
                                return e(implode(', ', $names));
                            },
                        ],
                        [
                            'label' => __db('accommodation'),
                            'render' => function ($escort) {
                                $room = $escort->currentRoomAssignment ?? null;

                                $accommodation = $escort->current_room_assignment_id
                                    ? $room?->hotel?->hotel_name .
                                            ' - ' .
                                            $room->roomType?->roomType?->value .
                                            ' - ' .
                                            $room?->room_number ??
                                        '-'
                                    : '-';
                                //;
                                return $accommodation ?? '-';
                            },
                        ],
                        [
                            'label' => __db('actions'),
                            'render' => function ($escort) use ($delegation) {
                                if (can(['unassign_escorts', 'escort_edit_escorts'])) {
                                    $unassignUrl = route('escorts.unassign', $escort->id);
                                    return '
                                    <form action="' .
                                        $unassignUrl .
                                        '" method="POST" class="unassign-escort-form" style="display:inline;">
                                        ' .
                                        csrf_field() .
                                        '
                                        <input type="hidden" name="delegation_id" value="' .
                                        $delegation->id .
                                        '" />
                                        <button type="submit" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-[10px] flex items-center gap-2 py-1 rounded-lg me-auto">
                                            <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                            </svg>
                                            <span>' .
                                        __db('unassign') .
                                        '</span>
                                        </button>
                                    </form>';
                                }
                                return '';
                            },
                        ],
                    ];
                @endphp

                <x-reusable-table :data="$delegation->escorts" table-id="escortsTable" :columns="$columns" :no-data-message="__db('no_data_found')" />
            </div>

        </div>
    </div>

    <hr class="mx-6 border-neutral-200 h-10">
    <div class="flex items-center justify-between mt-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('drivers') }} ({{ $delegation->drivers->count() }})</h2>

        @if ($delegation->canAssignServices())
            <div class="flex items-center gap-3">
                @directCanany(['add_drivers', 'driver_add_drivers'])
                    <a href="{{ route('drivers.index', ['delegation_id' => $delegation->id, 'assignment_mode' => 'driver']) }}"
                        class="bg-[#B68A35] text-white px-4 py-2 rounded-lg">{{ __db('add') . ' ' . __db('drivers') }}</a>
                @enddirectCanany
            </div>
        @endif
    </div>


    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                @php
                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'render' => fn($row, $key) => $key + 1,
                        ],
                        [
                            'label' => __db('driver') . ' ' . __db('code'),
                            'render' => function ($driver) {
                                $searchUrl = route('drivers.index', ['search' => $driver->code]);
                                return '<a href="' .
                                    $searchUrl .
                                    '" class="text-[#B68A35] hover:underline">' .
                                    e($driver->code) .
                                    '</a>';
                            },
                        ],
                        [
                            'label' => __db('military_number'),
                            'key' => 'military_number',
                            'render' => function ($driver) {
                                $searchUrl = route('drivers.index', ['search' => $driver->military_number]);
                                return '<a href="' .
                                    $searchUrl .
                                    '" class="text-[#B68A35] hover:underline">' .
                                    e($driver->military_number) .
                                    '</a>';
                            },
                        ],
                        [
                            'label' => __db('name'),
                            'key' => 'name',
                            'render' => function ($driver) {
                                $searchUrl = route('drivers.index', ['search' => $driver->name_en]);
                                $driverTitle = $driver?->getTranslation('title') ?? '';
                                $driverName = $driver?->getTranslation('name') ?? '';

                                return '<a href="' .
                                    $searchUrl .
                                    '" class="text-[#B68A35] hover:underline">' .
                                    e($driverTitle . ' ' . $driverName) .
                                    '</a>';
                            },
                        ],
                        [
                            'label' => __db('phone_number'),
                            'key' => 'phone_number',
                            'render' => fn($driver) => '<span dir="ltr">' . e($driver->phone_number) . '</span>',
                        ],
                        [
                            'label' => __db('car') . ' ' . __db('type'),
                            'key' => 'car_type',
                            'render' => fn($driver) => e($driver->car_type),
                        ],
                        [
                            'label' => __db('car') . ' ' . __db('number'),
                            'key' => 'car_number',
                            'render' => fn($driver) => e($driver->car_number),
                        ],
                        [
                            'label' => __db('capacity'),
                            'key' => 'capacity',
                            'render' => fn($driver) => e($driver->capacity),
                        ],
                        [
                            'label' => __db('accommodation'),
                            'render' => function ($driver) {
                                $room = $driver->currentRoomAssignment ?? null;

                                $accommodation = $driver->current_room_assignment_id
                                    ? $room?->hotel?->hotel_name .
                                            ' - ' .
                                            $room->roomType?->roomType?->value .
                                            ' - ' .
                                            $room?->room_number ??
                                        '-'
                                    : '-';
                                //;
                                return $accommodation ?? '-';
                            },
                        ],
                        [
                            'label' => __db('actions'),
                            'key' => 'actions',
                            'permission' => ['assign_drivers', 'driver_edit_drivers'],
                            'render' => function ($driver) use ($delegation) {
                                $editUrl = route('drivers.edit', $driver->id);
                                $output = '<div class="flex align-center gap-4">';

                                $output .=
                                    '<a href="' .
                                    $editUrl .
                                    '">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="#B68A35" d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/></svg>
                                        </a>';
                                if ($driver->status == 1) {
                                    $currentDelegationAssignment = $driver->delegations
                                        ->where('pivot.status', 1)
                                        ->where('id', $delegation->id)
                                        ->first();
                                    if ($currentDelegationAssignment) {
                                        $unassignUrl = route('drivers.unassign', $driver->id);
                                        $output .=
                                            '<form action="' .
                                            $unassignUrl .
                                            '" method="POST" class="unassign-driver-form" style="display:inline;">' .
                                            csrf_field() .
                                            '<input type="hidden" name="delegation_id" value="' .
                                            $delegation->id .
                                            '" />
                                                <button type="submit" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-[10px] flex items-center gap-2 py-1 rounded-lg me-auto">
                                                    <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                                    <span>' .
                                            __db('unassign') .
                                            '</span>
                                                </button>
                                            </form>';
                                    } else {
                                        $assignUrl = route('drivers.assignIndex', $driver->id);
                                        $output .=
                                            '<a href="' .
                                            $assignUrl .
                                            '" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-xs flex items-center gap-2 py-1 rounded-lg me-auto">
                                                <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                                <span>' .
                                            __db('assign') .
                                            '</span>
                                            </a>';
                                    }
                                }
                                $output .= '</div>';
                                return $output;
                            },
                        ],
                        //             [
                        //                 'label' => __db('status'),
                        //                 'key' => 'status',
                        //                 'render' => function ($driver) {
                        //                     return '<div class="flex items-center">
    //     <label for="switch-driver' .
                        //                         $driver->id .
                        //                         '" class="relative inline-block w-11 h-6">
    //         <input type="checkbox" id="switch-driver' .
                        //                         $driver->id .
                        //                         '" onchange="update_driver_status(this)" value="' .
                        //                         $driver->id .
                        //                         '" class="sr-only peer" ' .
                        //                         ($driver->status == 1 ? 'checked' : '') .
                        //                         ' />
    //         <div class="block bg-gray-300 peer-checked:bg-[#009448] w-11 h-6 rounded-full transition"></div>
    //         <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></div>
    //     </label>
    // </div>';
                        //                 },
                        //             ],
                    ];

                    $rowClass = function ($driver) {
                        return $driver->delegations->where('pivot.status', 1)->count() > 0 ? '' : 'bg-[#f2eccf]';
                    };

                @endphp


                <x-reusable-table :data="$delegation->drivers" table-id="driversTable" :columns="$columns" :no-data-message="__db('no_data_found')" />
            </div>

        </div>
    </div>

    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('interviews') }} ({{ $delegation->interviews->count() }})</h2>

    @php
        $columns = [
            [
                'label' => __db('sl_no'),
                'render' => fn($row, $key) => $key + 1,
            ],
            [
                'label' => __db('date_time'),
                'render' => fn($row) => $row->date_time
                    ? Carbon\Carbon::parse($row->date_time)->format('Y-m-d H:i')
                    : '-',
            ],
            [
                'label' => __db('attended_by'),
                'render' => function ($row) {
                    $attendees = $row->interviewMembers->where('type', 'from');
                    $names = $attendees
                        ->map(fn($im) => e($im->resolveMemberForInterview($row)?->getTranslation('name') ?? '-'))
                        ->filter()
                        ->implode('<br>');
                    return $names ?: '-';
                },
            ],
            [
                'label' => __db('interview_with'),
                'render' => function ($row) {
                    if (!empty($row->other_member_id) && $row->otherMember) {
                        $otherMemberName = $row->otherMember->name ?? '';
                        $otherMemberId = $row->otherMember->getTranslation('name') ?? $row->other_member_id;
                        if ($otherMemberId) {
                            $with =
                                '<a href="' .
                                route('other-interview-members.show', [
                                    'other_interview_member' => base64_encode($row->other_member_id),
                                ]) .
                                '" class="!text-[#B68A35]">
                                    <span class="block">' .
                                __db('other_member') .
                                ': ' .
                                e($row->otherMember->getTranslation('name')) .
                                '</span>
                                </a>';
                        }
                    } else {
                        $toMembers = $row->toMembers->where('type', 'to');
                        if ($toMembers->count() > 0) {
                            $delegateNames = $toMembers
                                ->map(function ($member) use ($row) {
                                    $delegate = $member->resolveMemberForInterview($row);
                                    if ($delegate) {
                                        if ($delegate instanceof \App\Models\Delegate) {
                                            return '<a href="' .
                                                route('delegations.show', $row->interviewWithDelegation->id ?? '') .
                                                '" class="block !text-[#B68A35]">' .
                                                e(
                                                    $delegate->getTranslation('title') .
                                                        ' ' .
                                                        $delegate->getTranslation('name'),
                                                ) .
                                                '</a>';
                                        } elseif ($delegate instanceof \App\Models\OtherInterviewMember) {
                                            return '<a href="' .
                                                route('other-interview-members.show', [
                                                    'other_interview_member' => base64_encode($delegate->id),
                                                ]) .
                                                '" class="block !text-[#B68A35]">' .
                                                __db('other_member') .
                                                ': ' .
                                                e($delegate->getTranslation('name')) .
                                                '</a>';
                                        }
                                    }
                                    return '';
                                })
                                ->filter()
                                ->implode('');

                            if (!empty($delegateNames)) {
                                $with = $delegateNames;
                            } else {
                                $with =
                                    '<a href="' .
                                    route('delegations.show', $row->interviewWithDelegation->id ?? '') .
                                    '" class="!text-[#B68A35]">' .
                                    ' ' .
                                    __db('delegation_id') .
                                    ' : ' .
                                    e($row->interviewWithDelegation->code ?? '') .
                                    '</a>';
                            }
                        } else {
                            $with =
                                '<a href="' .
                                route('delegations.show', $row->interviewWithDelegation->id ?? '') .
                                '" class="!text-[#B68A35]">' .
                                ' ' .
                                __db('delegation_id') .
                                ' : ' .
                                e($row->interviewWithDelegation->code ?? '') .
                                '</a>';
                        }
                    }

                    return $with;
                },
            ],
            ['label' => __db('status'), 'render' => fn($row) => e(ucfirst($row->status->value))],
        ];
        $data = $delegation->interviews ?? collect();
        $noDataMessage = __db('no_data_found');
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <x-reusable-table :columns="$columns" table-id="interviewsTable" :data="$data" :noDataMessage="$noDataMessage" />
            </div>
        </div>
    </div>


    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('attachments') }} ({{ $delegation->attachments->count() }})
    </h2>

    @php
        $columns = [
            [
                'label' => __db('sl_no'),
                'render' => fn($row, $key) => $key + 1,
            ],
            [
                'label' => __db('title'),
                'render' => fn($row) => $row->title?->value ?? '-',
            ],
            // [
            //     'label' => __db('file_name'),
            //     'render' => fn($row) => $row->file_name ?? '-',
            // ],
            [
                'label' => __db('uploaded_file'),
                'render' => function ($row) {
                    $fileUrl = $row->file_path ? asset('storage/' . $row->file_path) : '#';
                    $fileName = e($row->file_name);
                    return '<a href="' .
                        $fileUrl .
                        '" target="_blank" class="font-medium !text-[#B68A35]">' .
                        $fileName .
                        '</a>';
                },
            ],
            [
                'label' => __db('uploaded_date'),
                'render' => fn($row) => $row->created_at ? $row->created_at->format('d-m-Y') : '-',
            ],
            [
                'label' => __db('document_date'),
                'render' => fn($row) => $row->document_date ?? '-',
            ],
        ];

        $data = $delegation->attachments;
        $noDataMessage = __db('no_attachments_found');
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <x-reusable-table :columns="$columns" table-id="attachmentsTable" :data="$data" :noDataMessage="$noDataMessage" />
            </div>
        </div>
    </div>






    @foreach ($delegation->delegates as $delegate)
        <div id="delegate-transport-modal-{{ $delegate->id }}" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black bg-opacity-30 p-4 md:p-6">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-xl font-semibold text-blue-800 truncate">
                        {{ __db('transport_information_for') }} {{ $delegate->name_en ?? '-' }}
                    </h3>
                    <button type="button"
                        class="text-blue-600 hover:text-blue-800 rounded focus:outline-none focus:ring focus:ring-blue-300 p-1"
                        data-modal-hide="delegate-transport-modal-{{ $delegate->id }}" aria-label="Close modal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-5 space-y-8">
                    {{-- Arrival Section --}}
                    <section class="bg-blue-50 rounded-md p-4 border border-blue-200 shadow-sm">
                        <h4 class="text-lg font-semibold text-blue-700 mb-4 border-b border-blue-200 pb-2">
                            {{ __db('arrival') }}</h4>
                        @php $arrival = $delegate->delegateTransports->where('type', 'arrival')->first(); @endphp

                        @if ($arrival)
                            <div class="grid grid-cols-2 gap-x-8 gap-y-3 text-blue-900 text-sm">
                                <div class="border-b border-blue-200 pb-2">
                                    <p class="font-medium">{{ __db('to_airport') }}</p>
                                    <p>{{ $arrival->airport->value ?? '-' }}</p>
                                </div>
                                <div class="border-b border-blue-200 pb-2">
                                    <p class="font-medium">{{ __db('flight_no') }}</p>
                                    <p>{{ $arrival->flight_no ?? '-' }}</p>
                                </div>
                                <div class="border-b border-blue-200 pb-2">
                                    <p class="font-medium">{{ __db('flight_name') }}</p>
                                    <p>{{ $arrival->flight_name ?? '-' }}</p>
                                </div>
                                <div class="border-b border-blue-200 pb-2">
                                    <p class="font-medium">{{ __db('date_time') }}</p>
                                    <p>{{ $arrival->date_time ?? '-' }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-blue-400 italic">{{ __db('no_arrival_information') }}.</p>
                        @endif
                    </section>

                    {{-- Departure Section --}}
                    <section class="bg-orange-50 rounded-md p-4 border border-orange-200 shadow-sm">
                        <h4 class="text-lg font-semibold text-orange-700 mb-4 border-b border-orange-200 pb-2">
                            {{ __db('departure') }}</h4>
                        @php $departure = $delegate->delegateTransports->where('type', 'departure')->first(); @endphp

                        @if ($departure)
                            <div class="grid grid-cols-2 gap-x-8 gap-y-3 text-orange-900 text-sm">
                                <div class="border-b border-orange-200 pb-2">
                                    <p class="font-medium">{{ __db('from_airport') }}</p>
                                    <p>{{ $departure->airport->value ?? '-' }}</p>
                                </div>
                                <div class="border-b border-orange-200 pb-2">
                                    <p class="font-medium">{{ __db('flight_no') }}</p>
                                    <p>{{ $departure->flight_no ?? '-' }}</p>
                                </div>
                                <div class="border-b border-orange-200 pb-2">
                                    <p class="font-medium">{{ __db('flight_name') }}</p>
                                    <p>{{ $departure->flight_name ?? '-' }}</p>
                                </div>
                                <div class="border-b border-orange-200 pb-2">
                                    <p class="font-medium">{{ __db('date_time') }}</p>
                                    <p>{{ $departure->date_time ?? '-' }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-orange-400 italic">{{ __db('no_departure_information') }}.</p>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    @endforeach














@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.delete-delegate-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "{{ __db('are_you_sure') }}",
                        text: "{{ __db('delete_delegate_confirm_msg') }}",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "{{ __db('yes_delete') }}",
                        cancelButtonText: "{{ __db('cancel') }}",
                        customClass: {
                            popup: 'w-full max-w-2xl',
                            confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]',
                            cancelButton: 'px-4 rounded-lg'
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.unassign-escort-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "{{ __db('are_you_sure') }}",
                        text: "{{ __db('unassign_confirm_text') }}",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "{{ __db('yes_unassign') }}",
                        cancelButtonText: "{{ __db('cancel') }}",
                        customClass: {
                            popup: 'w-full max-w-2xl',
                            confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]',
                            cancelButton: 'px-4 rounded-lg'
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.unassign-driver-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "{{ __db('are_you_sure') }}",
                        text: "{{ __db('unassign_confirm_text') }}",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "{{ __db('yes_unassign') }}",
                        cancelButtonText: "{{ __db('cancel') }}",
                        customClass: {
                            popup: 'w-full max-w-2xl',
                            confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]',
                            cancelButton: 'px-4 rounded-lg'
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });

        function update_escort_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('escorts.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data.status == 'success') {
                    toastr.success("{{ __db('status_updated') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);

                } else {
                    toastr.error("{{ __db('something_went_wrong') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            });
        }

        function update_driver_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('drivers.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data.status == 'success') {
                    toastr.success("{{ __db('status_updated') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);

                } else {
                    toastr.error("{{ __db('something_went_wrong') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            });
        }
    </script>
@endsection
