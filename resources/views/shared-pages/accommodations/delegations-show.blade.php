    @if (!$delegation->canAssignServices() && $delegation?->invitationStatus?->code !== '1')
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <p><strong>{{ __db('Note') }}:</strong> {{ __db('delegation_cannot_assign_services') }}.
                {{ __db('delegation_has_status') }} "{{ $delegation->invitationStatus?->value }}"
                {{ __db('cannot_assign_these_services') }}.</p>
        </div>
    @endif

    <div>
        <div class="flex flex-wrap items-center justify-between gap-4 mb-10">
            <h2 class="font-semibold text-2xl">{{ __db('delegation') }}</h2>
            <div class="flex gap-3 ms-auto">
                <x-back-btn class=""
                    back-url="{{ session()->get('accommodation_delegations_last_url') ? session()->get('accommodation_delegations_last_url') : route('accommodation-delegations') }}" />
            </div>
        </div>

        @php
            $columns = [
                ['label' => __db('delegation_id'), 'render' => fn($row) => $row->code ?? '-'],
                ['label' => __db('invitation_from'), 'render' => fn($row) => $row->invitationFrom?->value ?? '-'],
                ['label' => __db('continent'), 'render' => fn($row) => $row->continent?->value ?? '-'],
                ['label' => __db('country'), 'render' => fn($row) => $row->country?->name ?? '-'],
                ['label' => __db('invitation_status'), 'render' => fn($row) => $row->invitationStatus?->value ?? '-'],
                [
                    'label' => __db('participation_status'),
                    'render' => fn($row) => $row->participationStatus?->value ?? '-',
                ],
            ];

            $data = [$delegation];
            $noDataMessage = __db('no_data_found');
        @endphp

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-4">
            <div class="xl:col-span-12">
                <div class="bg-white h-full w-full rounded-lg border-0 p-10">
                    <x-reusable-table :columns="$columns" :data="$data" :noDataMessage="$noDataMessage" />

                    <hr class="my-5">

                    @php
                        $note1_columns = [['label' => __db('note_1'), 'render' => fn($row) => $row->note1 ?? '-']];
                        $note2_columns = [['label' => __db('note_2'), 'render' => fn($row) => $row->note2 ?? '-']];
                        $data = [$delegation];
                        $noDataMessage = __db('no_data_found');
                    @endphp

                    <div class="grid grid-cols-2 gap-6 mt-3">
                        <x-reusable-table :columns="$note1_columns" :data="$data" :noDataMessage="$noDataMessage" />
                        <x-reusable-table :columns="$note2_columns" :data="$data" :noDataMessage="$noDataMessage" />
                    </div>
                </div>
            </div>
        </div>

        <hr class="mx-6 border-neutral-200 h-10">
        <div class="flex items-center justify-between mt-12">
            <h2 class="font-semibold mb-0 text-[18px] ">{{ __db('delegates') }} ({{ $delegation->delegates->count() }})
            </h2>

            @if ($delegation->canAssignServices())
                @directCanany(['assign_accommodations', 'hotel_assign_accommodations'])
                    <div class="items-center gap-3 ">
                        <select id="hotelDelegate" name="hotelDelegate"
                            class="select2 hotelSelection p-3 rounded-lg w-[300px] text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                            <option selected value="">{{ __db('select_hotel') }}</option>
                            @foreach ($hotels as $hot)
                                <option value="{{ $hot->id }}">{{ $hot->hotel_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @enddirectCanany
            @endif
        </div>

        <div id="HotelInfo" style="display: none;">
            <div class="bg-white p-4 w-auto rounded-lg border border-neutral-200 mt-3">
                <h4 class="font-semibold mb-3" id="hotelNameDelegate">{{ __db('room_details') }}</h4>
                <table class="min-w-full text-sm border border-neutral-300 rounded-lg">
                    <thead class="bg-neutral-100">
                        <tr class="text-[13px]">
                            <th class="p-2 border  text-start">{{ __db('room_type') }}</th>
                            <th class="p-2 border">{{ __db('total_rooms') }}</th>
                            <th class="p-2 border">{{ __db('assigned_rooms') }}</th>
                            <th class="p-2 border">{{ __db('available_rooms') }}</th>
                        </tr>
                    </thead>
                    <tbody id="roomDetails">

                    </tbody>
                </table>
            </div>
        </div>
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    @php
                        $columns = [
                            [
                                'label' =>
                                    '<input type="checkbox" id="select-all-delegates" class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded">',
                                'permission' => ['assign_accommodations', 'hotel_assign_accommodations'],
                                'render' => function ($row) {
                                    if ($row->accommodation == 1) {
                                        return '<input type="checkbox" class="assign-hotel-checkbox"
                                        data-delegate-id="' .
                                            e($row->id) .
                                            '" class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded">';
                                    }
                                },
                            ],
                            [
                                'label' => __db('sl_no'),
                                'render' => fn($row, $key) => $key + 1,
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
                                    return $badge . '<div class="block">' . e($title . ' / ' . $name) . '</div>';
                                },
                            ],
                            [
                                'label' => __db('designation'),
                                'render' => fn($row) => $row->getTranslation('designation') ?? '-',
                            ],
                            [
                                'label' => __db('internal_ranking'),
                                'render' => fn($row) => $row->internalRanking?->value ?? '-',
                            ],
                            [
                                'label' => __db('gender'),
                                'render' => fn($row) => $row->gender?->value ?? '-',
                            ],
                            [
                                'label' => __db('parent_id'),
                                'render' => fn($row) => $row->parent?->getTranslation('name') ?? '-',
                            ],
                            [
                                'label' => __db('relationship'),
                                'render' => fn($row) => $row->relationship?->value ?? '-',
                            ],
                            [
                                'label' => __db('badge_printed'),
                                'render' => fn($row) => $row->badge_printed ? 'Yes' : 'No',
                            ],
                            [
                                'label' => __db('participation_status'),
                                'render' => function ($row) {
                                    return $row->participation_status ? __db($row->participation_status) : '-';
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

                            [
                                'label' => __db('accommodation_required'),
                                'permission' => ['assign_accommodations', 'hotel_assign_accommodations'],
                                'render' => function ($row) {
                                    $room = $row->currentRoomAssignment ?? null;
                                    $isChecked = $row->accommodation == 1 ? 'checked' : '';

                                    return '<input type="checkbox" class="accommodation-status-checkbox"
                                    data-delegate-id="' .
                                        e($row->id) .
                                        '"
                                    ' .
                                        $isChecked .
                                        '
                                    class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded">';
                                },
                            ],

                            [
                                'label' => __db('hotel'),
                                'render' => function ($row) {
                                    $room = $row->currentRoomAssignment ?? null;
                                    return '<span class="hotel_name">' .
                                        $room?->hotel?->hotel_name .
                                        '</span>
                                    <input type="hidden" name="hotel_id" id="hotel_id' .
                                        $row->id .
                                        '"
                                        class="hotel-id-input" data-delegate-id="' .
                                        $row->id .
                                        '"
                                        value="' .
                                        $room?->hotel_id .
                                        '">';
                                },
                            ],

                            [
                                'label' => __db('room_type'),
                                'class' => 'w-[175px]',
                                'render' => function ($row) use ($delegation) {
                                    $room = $row->currentRoomAssignment ?? null;
                                    if (
                                        can(['assign_accommodations', 'hotel_assign_accommodations']) &&
                                        $delegation->canAssignServices()
                                    ) {
                                        if ($row->accommodation == 1) {
                                            $options = '';
                                            if ($room) {
                                                $hotelid = $room->hotel_id;
                                                $roomTypes = App\Models\AccommodationRoom::with('roomType')
                                                    ->where('accommodation_id', $hotelid)
                                                    ->get();
                                                foreach ($roomTypes as $roomType) {
                                                    $options .=
                                                        '<option value="' .
                                                        $roomType->id .
                                                        '" ' .
                                                        ($roomType->id == $room->room_type_id ? 'selected' : '') .
                                                        '>' .
                                                        $roomType->roomType?->value .
                                                        '</option>';
                                                }
                                            }

                                            return '<select name="room_type" id="room_type' .
                                                $row->id .
                                                '"
                                            class="room-type-dropdown p-1 rounded-lg min-w-[150px] text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                            <option value="">' .
                                                __db('select') .
                                                '</option>
                                            ' .
                                                $options .
                                                '
                                        </select>';
                                        }
                                    } else {
                                        return $room?->roomType?->roomType?->value;
                                    }
                                },
                            ],

                            [
                                'label' => __db('room_number'),
                                'class' => '',
                                'render' => function ($row) use ($delegation) {
                                    $room = $row->currentRoomAssignment ?? null;
                                    if (
                                        can(['assign_accommodations', 'hotel_assign_accommodations']) &&
                                        $delegation->canAssignServices()
                                    ) {
                                        if ($row->accommodation == 1) {
                                            return '<input type="text" name="room_number" id="room_number' .
                                                $row->id .
                                                '"
                                            class="room-number-input w-[75px] p-1 rounded-lg text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                            value="' .
                                                $room?->room_number .
                                                '">';
                                        }
                                    } else {
                                        return $room?->room_number;
                                    }
                                },
                            ],
                            [
                                'label' => __db('action'),
                                'permission' => ['assign_accommodations', 'hotel_assign_accommodations'],
                                'render' => function ($row) use ($delegation) {
                                    $room = $row->currentRoomAssignment ?? null;

                                    $action = '<div class="flex items-center gap-1">';
                                    if ($row->accommodation == 1 && $delegation->canAssignServices()) {
                                        $action .=
                                            '<a href="#" id="add-attachment-btn"
                                                    class="save-room-assignment text-xs !bg-[#B68A35] w-xs text-center text-white rounded-lg py-1 px-2">
                                                    <span>' .
                                            __db('save') .
                                            ' </span>
                                                </a>
                                            ';
                                    }
                                    if ($room) {
                                        $action .=
                                            '<a href="#"  class="remove-room-assignment text-xs bg-red-600 text-white rounded-lg py-1 px-2" data-assignment-id="' .
                                            $room->id .
                                            '">
                                                ' .
                                            __db('remove') .
                                            '
                                                </a>';
                                    }
                                    $action .= '</div>';
                                    return $action;
                                },
                            ],
                        ];
                        $data = $delegation->delegates;

                        $noDataMessage = __db('no_delegates_found');
                    @endphp

                    <x-reusable-table :columns="$columns" table-id="delegatesTable" :enableColumnListBtn="true" :data="$data"
                        :noDataMessage="$noDataMessage" :rowClass="function ($row) {
                            $room = $row->currentRoomAssignment ?? null;
                        
                            if ($row->accommodation == 0) {
                                return 'bg-[#e5e5e5]';
                            } elseif ($room) {
                                return 'bg-[#acf3bc]';
                            } else {
                                return ''; // White background when accommodation is required but no room assigned
                            }
                        }" />


                    <hr class="my-5">
                    <div class="flex items-center justify-start gap-6">

                        <div class="mt-3 flex items-center justify-start gap-3 ">
                            <div class="h-5 w-5 bg-[#e5e5e5] rounded"></div>
                            <span class="text-gray-800 text-sm">{{ __db('accommodation_not_required') }}</span>
                        </div>

                        <div class="mt-3 flex items-center justify-start gap-3 ">
                            <div class="h-5 w-5 bg-[#acf3bc] rounded"></div>
                            <span class="text-gray-800 text-sm">{{ __db('assigned') }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <hr class="mx-6 border-neutral-200 h-2">

        <div class="flex items-center justify-between mt-12">
            <h2 class="font-semibold mb-0 text-[18px] ">{{ __db('escorts') }} ({{ $delegation->escorts->count() }})
            </h2>

            @if ($delegation->canAssignServices())
                @directCanany(['assign_accommodations', 'hotel_assign_accommodations'])
                    <div class="items-center gap-3 ">
                        <select id="hotelEscort" name="hotelEscort"
                            class="select2 hotelSelection p-3 rounded-lg w-[300px] text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                            <option selected value="">{{ __db('select_hotel') }}</option>
                            @foreach ($hotels as $hot)
                                <option value="{{ $hot->id }}">{{ $hot->hotel_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @enddirectCanany
            @endif
        </div>

        <div id="HotelInfoEscort" style="display: none;">
            <div class="bg-white p-4 w-auto rounded-lg border border-neutral-200 mt-3">
                <h4 class="font-semibold mb-3" id="hotelNameEscort">{{ __db('room_details') }}</h4>
                <table class="min-w-full text-sm border border-neutral-300 rounded-lg">
                    <thead class="bg-neutral-100">
                        <tr class="text-[13px]">
                            <th class="p-2 border  text-start">{{ __db('room_type') }}</th>
                            <th class="p-2 border">{{ __db('total_rooms') }}</th>
                            <th class="p-2 border">{{ __db('assigned_rooms') }}</th>
                            <th class="p-2 border">{{ __db('available_rooms') }}</th>
                        </tr>
                    </thead>
                    <tbody id="roomDetailsEscort">

                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                        <thead>
                            <tr class="text-[13px]">
                                @directCanany(['assign_accommodations', 'hotel_assign_accommodations'])
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                        <input type="checkbox" id="select-all-escorts"
                                            class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded">
                                    </th>
                                @enddirectCanany
                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('sl_no') }}</th>
                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('military_number') }}</th>

                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('name') }}
                                </th>
                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('mobile') }}</th>
                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('gender') }}</th>
                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('hotel') }}</th>
                                <th scope="col"
                                    class="p-2 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('room_type') }}</th>

                                <th scope="col"
                                    class="p-2 w-[115px] !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('room_number') }}</th>


                                @directCanany(['assign_accommodations', 'hotel_assign_accommodations'])
                                    <th scope="col"
                                        class="p-2 w-[60px] !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                        {{ __db('action') }} </th>
                                @enddirectCanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($delegation->escorts as $keyEscort => $rowEscort)
                                @php
                                    $idEscort = $rowEscort->id ?? uniqid();

                                    $roomEscort = $rowEscort->currentRoomAssignment ?? null;

                                @endphp

                                <tr data-id="{{ $rowEscort->id }}"
                                    class="escort-row text-[12px] align-[middle] @if ($roomEscort) bg-[#acf3bc] @endif">
                                    @directCanany(['assign_accommodations', 'hotel_assign_accommodations'])
                                        <td class="text-center px-1 py-2 border border-gray-200">
                                            <input type="checkbox" class="assign-hotel-checkbox-escort"
                                                data-escort-id="{{ $rowEscort->id }}"
                                                class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded">
                                        </td>
                                    @enddirectCanany
                                    <td class="text-center px-1 py-2 border border-gray-200">{{ $keyEscort + 1 }}</td>
                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        {{ $rowEscort->military_number ?? '-' }}
                                    </td>
                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        <div class="block">
                                            {{ $rowEscort->getTranslation('title') . ' ' . $rowEscort->getTranslation('name') }}
                                        </div>
                                    </td>
                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        {{ $rowEscort->phone_number ?? '-' }}
                                    </td>
                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        {{ $rowEscort->gender?->value ?? '-' }}
                                    </td>

                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        @if (can(['assign_accommodations', 'hotel_assign_accommodations']) && $delegation->canAssignServices())
                                            <span
                                                class="hotel_name_escort">{{ $roomEscort?->hotel?->hotel_name ?? '' }}</span>
                                            <input type="hidden" name="hotel_id_escort"
                                                id="hotel_id_escort{{ $rowEscort->id }}"
                                                class="hotel-id-input-escort" data-escort-id="{{ $rowEscort->id }}"
                                                value="{{ $roomEscort?->hotel_id ?? '' }}">
                                        @else
                                            {{ $roomEscort?->hotel?->hotel_name ?? '' }}
                                        @endif
                                    </td>
                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        @if (can(['assign_accommodations', 'hotel_assign_accommodations']) && $delegation->canAssignServices())
                                            @php
                                                $optionsEscort = '';
                                                if ($roomEscort) {
                                                    $hotelidEscort = $roomEscort->hotel_id;
                                                    $roomTypesEscort = App\Models\AccommodationRoom::with('roomType')
                                                        ->where('accommodation_id', $hotelidEscort)
                                                        ->get();
                                                    foreach ($roomTypesEscort as $roomTypeEscort) {
                                                        $optionsEscort .=
                                                            '<option value="' .
                                                            $roomTypeEscort->id .
                                                            '" ' .
                                                            ($roomTypeEscort->id == $roomEscort->room_type_id
                                                                ? 'selected'
                                                                : '') .
                                                            '>' .
                                                            $roomTypeEscort->roomType?->value .
                                                            '</option>';
                                                    }
                                                }
                                            @endphp

                                            <select name="room_type_escort" id="room_type_escort"
                                                class="room-type-dropdown-escort p-1 rounded-lg min-w-[150px] text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                                <option value="">{{ __db('select') }}</option>
                                                {!! $optionsEscort !!}
                                            </select>
                                        @else
                                            {{ $roomEscort?->roomType?->roomType?->value ?? '' }}
                                        @endif
                                    </td>

                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        @if (can(['assign_accommodations', 'hotel_assign_accommodations']) && $delegation->canAssignServices())
                                            <input type="text" name="room_number_escort" id="room_number_escort"
                                                class="room-number-input-escort w-[75px] p-1 rounded-lg text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                                value="{{ $roomEscort?->room_number ?? '' }}">
                                        @else
                                            {{ $roomEscort?->room_number ?? '' }}
                                        @endif
                                    </td>

                                    @if ($delegation->canAssignServices())
                                        @directCanany(['assign_accommodations', 'hotel_assign_accommodations'])
                                            <td class="text-center px-1 py-3 border border-gray-200">
                                                <div class="flex items-center gap-1">
                                                    <a href="#" id="add-attachment-btn"
                                                        class="save-room-assignment-escort text-xs !bg-[#B68A35] w-xs text-center text-white rounded-lg py-1 px-2">
                                                        <span>{{ __db('save') }} </span>
                                                    </a>

                                                    @if ($roomEscort)
                                                        <a href="#"
                                                            class="remove-room-assignment text-xs bg-red-600 text-white rounded-lg py-1 px-2"
                                                            data-assignment-id="{{ $roomEscort->id }}">
                                                            {{ __db('remove') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        @enddirectCanany
                                    @endif
                                </tr>
                            @empty
                                <tr class="border-t">
                                    <td class="px-1 py-3 text-center " colspan="14" dir="ltr">
                                        {{ __db('no_data_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <hr class="my-5">
                    <div class="flex items-center justify-start gap-6">

                        <div class="mt-3 flex items-center justify-start gap-3 ">
                            <div class="h-5 w-5 bg-[#acf3bc] rounded"></div>
                            <span class="text-gray-800 text-sm">{{ __db('assigned') }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <hr class="mx-6 border-neutral-200 h-2">

        <div class="flex items-center justify-between mt-12">
            <h2 class="font-semibold mb-0 text-[18px] ">{{ __db('drivers') }} ({{ $delegation->drivers->count() }})
            </h2>

            @if ($delegation->canAssignServices())
                @directCanany(['assign_accommodations', 'hotel_assign_accommodations'])
                    <div class="items-center gap-3 ">
                        <select id="hotelDriver" name="hotelDriver"
                            class="select2 hotelSelection p-3 rounded-lg w-[300px] text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                            <option selected value="">{{ __db('select_hotel') }}</option>
                            @foreach ($hotels as $hot)
                                <option value="{{ $hot->id }}">{{ $hot->hotel_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @enddirectCanany
            @endif
        </div>

        <div id="HotelInfoDriver" style="display: none;">
            <div class="bg-white p-4 w-auto rounded-lg border border-neutral-200 mt-3">
                <h4 class="font-semibold mb-3" id="hotelNameDriver">{{ __db('room_details') }}</h4>
                <table class="min-w-full text-sm border border-neutral-300 rounded-lg">
                    <thead class="bg-neutral-100">
                        <tr class="text-[13px]">
                            <th class="p-2 border  text-start">{{ __db('room_type') }}</th>
                            <th class="p-2 border">{{ __db('total_rooms') }}</th>
                            <th class="p-2 border">{{ __db('assigned_rooms') }}</th>
                            <th class="p-2 border">{{ __db('available_rooms') }}</th>
                        </tr>
                    </thead>
                    <tbody id="roomDetailsDriver">

                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                        <thead>
                            <tr class="text-[13px]">
                                @directCanany(['assign_accommodations', 'hotel_assign_accommodations'])
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                        <input type="checkbox" id="select-all-drivers"
                                            class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded">
                                    </th>
                                @enddirectCanany
                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('sl_no') }}</th>
                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('military_number') }}</th>

                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('name') }}
                                </th>
                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('mobile') }}</th>

                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('hotel') }}</th>
                                <th scope="col"
                                    class="p-2 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('room_type') }}</th>

                                <th scope="col"
                                    class="p-2  !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('room_number') }}</th>


                                @directCanany(['assign_accommodations', 'hotel_assign_accommodations'])
                                    <th scope="col"
                                        class="p-2 w-[60px] !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                        {{ __db('action') }} </th>
                                @enddirectCanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($delegation->drivers as $keyDriver => $rowDriver)
                                @php
                                    $idDriver = $rowDriver->id ?? uniqid();

                                    $roomDriver = $rowDriver->currentRoomAssignment ?? null;

                                    if ($rowDriver->accommodation == 0) {
                                        $rowColor = 'bg-[#e5e5e5]';
                                    } elseif ($roomDriver) {
                                        $rowColor = 'bg-[#acf3bc]';
                                    } else {
                                        $rowColor = ''; // White background when accommodation is required but no room assigned
                                    }
                                @endphp

                                <tr data-id="{{ $rowDriver->id }}"
                                    class="driver-row text-[12px] align-[middle] {{ $rowColor }}">
                                    @directCanany(['assign_accommodations', 'hotel_assign_accommodations'])
                                        <td class="text-center px-1 py-2 border border-gray-200">
                                            @if ($rowDriver->accommodation == 1)
                                                <input type="checkbox" class="assign-hotel-checkbox-driver"
                                                    data-driver-id="{{ $rowDriver->id }}"
                                                    class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded">
                                            @endif
                                        </td>
                                    @enddirectCanany
                                    <td class="text-center px-1 py-2 border border-gray-200">{{ $keyDriver + 1 }}</td>

                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        {{ $rowDriver->military_number ?? '-' }}
                                    </td>
                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        <div class="block">
                                            {{ $rowDriver->getTranslation('title') . ' ' . $rowDriver->getTranslation('name') }}
                                        </div>
                                    </td>
                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        {{ $rowDriver->phone_number ?? '-' }}
                                    </td>

                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        @if (can(['assign_accommodations', 'hotel_assign_accommodations']) && $delegation->canAssignServices())
                                            <span
                                                class="hotel_name_driver">{{ $roomDriver?->hotel?->hotel_name ?? '' }}</span>
                                            <input type="hidden" name="hotel_id_driver"
                                                id="hotel_id_driver{{ $rowDriver->id }}"
                                                class="hotel-id-input-driver" data-driver-id="{{ $rowDriver->id }}"
                                                value="{{ $roomDriver?->hotel_id ?? '' }}">
                                        @else
                                            {{ $roomDriver?->hotel?->hotel_name ?? '' }}
                                        @endif
                                    </td>
                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        @if (can(['assign_accommodations', 'hotel_assign_accommodations']) && $delegation->canAssignServices())
                                            @if ($rowDriver->accommodation == 1)
                                                @php
                                                    $optionsDriver = '';
                                                    if ($roomDriver) {
                                                        $hotelidDriver = $roomDriver->hotel_id;
                                                        $roomTypesDriver = App\Models\AccommodationRoom::with(
                                                            'roomType',
                                                        )
                                                            ->where('accommodation_id', $hotelidDriver)
                                                            ->get();
                                                        foreach ($roomTypesDriver as $roomTypeDriver) {
                                                            $optionsDriver .=
                                                                '<option value="' .
                                                                $roomTypeDriver->id .
                                                                '" ' .
                                                                ($roomTypeDriver->id == $roomDriver->room_type_id
                                                                    ? 'selected'
                                                                    : '') .
                                                                '>' .
                                                                $roomTypeDriver->roomType?->value .
                                                                '</option>';
                                                        }
                                                    }
                                                @endphp

                                                <select name="room_type_driver" id="room_type_driver"
                                                    class="room-type-dropdown-driver p-1 rounded-lg min-w-[150px] text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                                    <option value="">{{ __db('select') }}</option>
                                                    {!! $optionsDriver !!}
                                                </select>
                                            @else
                                                {{ $roomDriver?->roomType?->roomType?->value ?? '' }}
                                            @endif
                                        @else
                                            {{ $roomDriver?->roomType?->roomType?->value ?? '' }}
                                        @endif
                                    </td>

                                    <td class="text-center px-1 border border-gray-200 py-3">
                                        @if (can(['assign_accommodations', 'hotel_assign_accommodations']) && $delegation->canAssignServices())
                                            @if ($rowDriver->accommodation == 1)
                                                <input type="text" name="room_number_driver"
                                                    id="room_number_driver"
                                                    class="room-number-input-driver w-[75px] p-1 rounded-lg text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                                    value="{{ $roomDriver?->room_number ?? '' }}">
                                            @else
                                                {{ $roomDriver?->room_number ?? '' }}
                                            @endif
                                        @else
                                            {{ $roomDriver?->room_number ?? '' }}
                                        @endif
                                    </td>

                                    @directCanany(['assign_accommodations', 'hotel_assign_accommodations'])
                                        <td class="text-center px-1 py-3 border border-gray-200">
                                            @if ($rowDriver->accommodation == 1)
                                                <div class="flex items-center gap-1">
                                                    <a href="#" id="add-attachment-btn"
                                                        class="save-room-assignment-driver text-xs !bg-[#B68A35] w-xs text-center text-white rounded-lg py-1 px-2">
                                                        <span>{{ __db('save') }} </span>
                                                    </a>

                                                    @if ($roomDriver)
                                                        <a href="#"
                                                            class="remove-room-assignment text-xs bg-red-600 text-white rounded-lg py-1 px-2"
                                                            data-assignment-id="{{ $roomDriver->id }}">
                                                            {{ __db('remove') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    @enddirectCanany
                                </tr>
                            @empty
                                <tr class="border-t">
                                    <td class="px-1 py-3 text-center " colspan="14" dir="ltr">
                                        {{ __db('no_data_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <hr class="my-5">
                    <div class="flex items-center justify-start gap-6">
                        <div class="mt-3 flex items-center justify-start gap-3 ">
                            <div class="h-5 w-5 bg-[#e5e5e5] rounded"></div>
                            <span class="text-gray-800 text-sm">{{ __db('accommodation_not_required') }}</span>
                        </div>
                        <div class="mt-3 flex items-center justify-start gap-3 ">
                            <div class="h-5 w-5 bg-[#acf3bc] rounded"></div>
                            <span class="text-gray-800 text-sm">{{ __db('assigned') }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('interviews') }}
            ({{ $delegation->interviews->count() }})
        </h2>

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
                ['label' => __db('status'), 'render' => fn($row) => e(ucfirst($row->status?->value))],
                [
                    'label' => __db('note'),
                    'render' => function ($row) {
                        return '<div class="break-words whitespace-normal max-w-xs">' . e($row?->comment) . '</div>';
                    },
                ],
            ];
            $data = $delegation->interviews ?? collect();
            $noDataMessage = __db('no_data_found');
        @endphp

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    <x-reusable-table :columns="$columns" table-id="interviewsTable" :data="$data"
                        :noDataMessage="$noDataMessage" />
                </div>
            </div>
        </div>


        <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('attachments') }}
            ({{ $delegation->attachments->count() }})
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
                    <x-reusable-table :columns="$columns" table-id="attachmentsTable" :data="$data"
                        :noDataMessage="$noDataMessage" />
                </div>
            </div>
        </div>
    </div>




    @foreach ($delegation->delegates as $delegate)
        <div id="delegate-transport-modal-{{ $delegate->id }}" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:p-6">
            <div class="relative w-full max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ __db('transport_information_for') }} {{ $delegate->name_en ?? '-' }}
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="delegate-transport-modal-{{ $delegate->id }}" aria-label="Close modal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Arrival Section --}}
                    <section>
                        <h4 class="text-xl font-semibold text-gray-900 pb-2">{{ __db('arrival') }}</h4>
                        @php $arrival = $delegate->delegateTransports->where('type', 'arrival')->first(); @endphp
                        <div class="border rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 bg-gray-50">
                            @if ($arrival)
                                <div class="border-b md:border-b-0 md:border-r border-gray-300 pb-4 pr-4">
                                    <p class="font-medium text-gray-600">{{ __db('to_airport') }}</p>
                                    <p class="text-base text-gray-900">{{ $arrival->airport?->value ?? '-' }}</p>
                                </div>
                                <div class="border-b md:border-b-0 border-gray-300 pb-4">
                                    <p class="font-medium text-gray-600">{{ __db('flight_no') }}</p>
                                    <p class="text-base text-gray-900">{{ $arrival->flight_no ?? '-' }}</p>
                                </div>
                                <div class="py-4 pr-4 md:border-r md:border-gray-300">
                                    <p class="font-medium text-gray-600">{{ __db('flight_name') }}</p>
                                    <p class="text-base text-gray-900">{{ $arrival->flight_name ?? '-' }}</p>
                                </div>
                                <div class="py-4 !pb-0">
                                    <p class="font-medium text-gray-600">{{ __db('date_time') }}</p>
                                    <p class="text-base text-gray-900">{{ $arrival->date_time ?? '-' }}</p>
                                </div>
                            @else
                                <p class="col-span-2 text-gray-500">{{ __db('no_arrival_information') }}.</p>
                            @endif
                        </div>
                    </section>

                    {{-- Departure Section --}}
                    <section>
                        <h4 class="text-xl font-semibold text-gray-900 pb-2">{{ __db('departure') }}</h4>
                        @php $departure = $delegate->delegateTransports->where('type', 'departure')->first(); @endphp
                        <div class="border rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 bg-gray-50">
                            @if ($departure)
                                <div class="border-b md:border-b-0 md:border-r border-gray-300 pb-4 pr-4">
                                    <p class="font-medium text-gray-600">{{ __db('from_airport') }}</p>
                                    <p class="text-base text-gray-900">{{ $departure->airport?->value ?? '-' }}</p>
                                </div>
                                <div class="border-b md:border-b-0 border-gray-300 pb-4">
                                    <p class="font-medium text-gray-600">{{ __db('flight_no') }}</p>
                                    <p class="text-base text-gray-900">{{ $departure->flight_no ?? '-' }}</p>
                                </div>
                                <div class="py-4 pr-4 md:border-r md:border-gray-300">
                                    <p class="font-medium text-gray-600">{{ __db('flight_name') }}</p>
                                    <p class="text-base text-gray-900">{{ $departure->flight_name ?? '-' }}</p>
                                </div>
                                <div class="py-4 !pb-0">
                                    <p class="font-medium text-gray-600">{{ __db('date_time') }}</p>
                                    <p class="text-base text-gray-900">{{ $departure->date_time ?? '-' }}</p>
                                </div>
                            @else
                                <p class="col-span-2 text-gray-500">{{ __db('no_departure_information') }}.</p>
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @endforeach









    @section('script')
        <script>
            document.addEventListener("DOMContentLoaded", () => {

                $(document).on("change", ".room-type-dropdown-escort", function() {
                    $(this).closest("tr").find(".room-number-input-escort").val("");
                });

                $(document).on("change", ".room-type-dropdown", function() {
                    $(this).closest("tr").find(".room-number-input").val("");
                });
                $(document).on("change", ".room-type-dropdown-driver", function() {
                    $(this).closest("tr").find(".room-number-input-driver").val("");
                });

                function hotelData(hotelId, section) {
                    let url = "{{ route('accommodation.rooms', ':id') }}";
                    url = url.replace(':id', hotelId);

                    if (hotelId) {
                        $.get(url, function(data) {
                            let html = '';
                            let hotelName = '';
                            if (data.length > 0) {
                                data.forEach(r => {
                                    hotelName = r.accommodation?.hotel_name ?? '';
                                    let available = r.total_rooms - r.assigned_rooms;
                                    html += `
                                <tr class="text-[12px]">
                                    <td class="p-2 border">${r.room_type?.value ?? '-'}</td>
                                    <td class="p-2 border text-center">${r.total_rooms}</td>
                                    <td class="p-2 border text-center">${r.assigned_rooms}</td>
                                    <td class="p-2 border text-center font-semibold">${available}</td>
                                </tr>
                            `;
                                });
                            } else {
                                html =
                                    `<tr><td colspan="4" class="p-2 border text-center">{{ __db('no_rooms_found') }}</td></tr>`;
                            }

                            if (section == 'Delegate') {
                                $('#hotelNameDelegate').html(hotelName);
                                $('#roomDetails').html(html);
                                $('#HotelInfo').show();

                                $('#hotelNameEscort').html('');
                                $('#roomDetailsEscort').html('');
                                $('#HotelInfoEscort').hide();
                                $('#hotelEscort').val('').trigger('change');

                                $('#hotelNameDriver').html('');
                                $('#roomDetailsDriver').html('');
                                $('#HotelInfoDriver').hide();
                                $('#hotelDriver').val('').trigger('change');
                            } else if (section == 'Escort') {
                                $('#hotelNameEscort').html(hotelName);
                                $('#roomDetailsEscort').html(html);
                                $('#HotelInfoEscort').show();

                                $('#hotelNameDelegate').html('');
                                $('#roomDetails').html('');
                                $('#HotelInfo').hide();
                                $('#hotelDelegate').val('').trigger('change');

                                $('#hotelNameDriver').html('');
                                $('#roomDetailsDriver').html('');
                                $('#HotelInfoDriver').hide();
                                $('#hotelDriver').val('').trigger('change');
                            } else if (section == 'Driver') {
                                $('#hotelNameDriver').html(hotelName);
                                $('#roomDetailsDriver').html(html);
                                $('#HotelInfoDriver').show();

                                $('#hotelNameDelegate').html('');
                                $('#roomDetails').html('');
                                $('#HotelInfo').hide();
                                $('#hotelDelegate').val('').trigger('change');

                                $('#hotelNameEscort').html('');
                                $('#roomDetailsEscort').html('');
                                $('#HotelInfoEscort').hide();
                                $('#hotelEscort').val('').trigger('change');
                            }

                        });
                    }

                }
                $(document).on('change', '#hotelDelegate', function() {
                    $('.assign-hotel-checkbox').prop('checked', false);

                    let hotelId = this.value;
                    hotelData(hotelId, 'Delegate');
                });

                $('.assign-hotel-checkbox').on('change', function() {
                    let hotelId = $('#hotelDelegate').val();
                    let Hotelname = $('#hotelDelegate option:selected').text();

                    if (!hotelId) {
                        this.checked = false;
                        toastr.error('{{ __db('please_select_hotel') }}');
                        return;
                    }

                    let row = $(this).closest('tr');
                    let delegateId = row.data('id');
                    let dropdown = row.find('.room-type-dropdown');
                    let hotel_name = row.find('.hotel_name');

                    if (this.checked) {
                        let url = "{{ route('accommodation.rooms', ':id') }}";
                        url = url.replace(':id', hotelId);

                        $.get(url, function(data) {
                            dropdown.empty().append('<option value="">{{ __db('select') }}</option>');
                            data.forEach(function(room) {
                                let available = room.total_rooms - room.assigned_rooms;
                                if (available > 0) {
                                    dropdown.append('<option value="' + room.id + '">' + room
                                        .room_type?.value + '</option>');
                                }
                            });
                        });

                        hotel_name.text(Hotelname);
                        $('#hotel_id' + delegateId).val(hotelId);
                    } else {
                        dropdown.empty().append('<option value="">{{ __db('select') }}</option>');
                    }
                });

                $('.save-room-assignment').on('click', function(e) {
                    e.preventDefault();
                    let row = $(this).closest('tr');
                    let checkboxDel = row.find('.assign-hotel-checkbox');
                    let delegateId = row.data('id');
                    let hotelId = $('#hotel_id' + delegateId).val();
                    let roomTypeId = row.find('.room-type-dropdown').val();
                    let roomNumber = row.find('.room-number-input').val();

                    if (!hotelId || !roomTypeId) {
                        toastr.error('{{ __db('please_select_room_details') }}');
                        return;
                    }

                    $.post("{{ route('accommodation.assign-rooms') }}", {
                        _token: '{{ csrf_token() }}',
                        assignable_id: delegateId,
                        assignable_type: 'Delegate',
                        hotel_id: hotelId,
                        room_type_id: roomTypeId,
                        room_number: roomNumber,
                        delegation_id: {{ $delegation->id }}
                    }, function(res) {
                        if (res.success === 1) {
                            row.css('background-color', '#acf3bc');
                            toastr.success('{{ __db('room_assigned') }}');

                            let actionCellDel = row.find('td').last(); // assuming last cell is action
                            if (actionCellDel.find('.remove-room-assignment').length === 0) {
                                let removeBtnHtml = `
                                <a href="#" class="remove-room-assignment text-xs bg-red-600 text-white rounded-lg py-1 px-3 ml-2" data-assignment-id="${res.assignment_id}">
                                    {{ __db('remove') }}
                                </a>`;
                                actionCellDel.append(removeBtnHtml);
                            } else {
                                let actionCellDel = row.find('td').last();
                                let removeBtn = actionCellDel.find('.remove-room-assignment')
                                removeBtn.attr('data-assignment-id', res.assignment_id).show();
                            }
                        } else if (res.success === 0) {
                            toastr.success('{{ __db('room_already_assigned') }}');
                        } else if (res.success === 2) {
                            toastr.error('{{ __db('room_not_available') }}');
                        } else if (res.success === 3) {
                            toastr.error('{{ __db('room_already_booked_by_external_member') }}');
                        } else if (res.success === 5) {
                            let existingUsersHtml = '';
                            if (res.existing_users && res.existing_users.length > 0) {
                                res.existing_users.forEach(function(user) {
                                    existingUsersHtml +=
                                        `<div>${user.name} (${user.type})</div>`;
                                });
                            } else {
                                existingUsersHtml = '<div>{{ __db('unknown_user') }}</div>';
                            }

                            Swal.fire({
                                title: "{{ __db('confirm_room_assignment') }}",
                                html: `<p>{{ __db('room_already_assigned_to_users') }}:</p>
                                   <div class="text-left bg-gray-100 p-3 rounded mt-2">${existingUsersHtml}</div>
                                   <p class="mt-3"><strong>{{ __db('hotel') }}:</strong> ${res.hotel_name}</p>
                                   <p><strong>{{ __db('room_number') }}:</strong> ${res.room_number}</p>
                                   <p><strong>{{ __db('room_type') }}:</strong> ${res.room_type}</p>
                                   <p class="text-red-600 font-bold mt-3">{{ __db('confirm_proceed_assignment') }}</p>`,
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: "{{ __db('yes_proceed') }}",
                                cancelButtonText: "{{ __db('cancel') }}",
                                customClass: {
                                    popup: 'w-full max-w-2xl',
                                    confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]',
                                    cancelButton: 'px-4 rounded-lg'
                                },
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.post("{{ route('accommodation.assign-rooms') }}", {
                                        _token: '{{ csrf_token() }}',
                                        assignable_id: delegateId,
                                        assignable_type: 'Delegate',
                                        hotel_id: hotelId,
                                        room_type_id: roomTypeId,
                                        room_number: roomNumber,
                                        delegation_id: {{ $delegation->id }},
                                        confirm_duplicate: 1
                                    }, function(res2) {
                                        if (res2.success === 1) {
                                            row.css('background-color', '#acf3bc');
                                            toastr.success(
                                                '{{ __db('room_assigned') }}');

                                            let actionCellDel = row.find('td').last();
                                            if (actionCellDel.find(
                                                    '.remove-room-assignment')
                                                .length === 0) {
                                                let removeBtnHtml = `
                                                <a href="#" class="remove-room-assignment text-xs bg-red-600 text-white rounded-lg py-1 px-3 ml-2" data-assignment-id="${res2.assignment_id}">
                                                    {{ __db('remove') }}
                                                </a>`;
                                                actionCellDel.append(removeBtnHtml);
                                            } else {
                                                let actionCellDel = row.find('td')
                                                    .last();
                                                let removeBtn = actionCellDel.find(
                                                    '.remove-room-assignment')
                                                removeBtn.attr('data-assignment-id',
                                                    res2.assignment_id).show();
                                            }
                                        } else {
                                            if (res2.success === 2) {
                                                toastr.error(
                                                    '{{ __db('room_not_available') }}'
                                                );
                                            } else if (res2.success === 3) {
                                                toastr.error(
                                                    '{{ __db('room_already_booked_by_external_member') }}'
                                                );
                                            }
                                        }
                                        checkboxDel.prop('checked', false);

                                        if ($('#hotelDelegate').val() == hotelId) {
                                            hotelData(hotelId, 'Delegate');
                                        }
                                    });
                                } else if (result.isDismissed) {
                                    row.find('.room-type-dropdown').val('').trigger('change');
                                    row.find('.room-number-input').val('');
                                }
                            });
                        } else if (res.success == 6) {
                            let differentRoomTypesHtml = '<ul class="list-disc pl-5 mt-2 text-left">';
                            if (res.different_room_types && res.different_room_types.length > 0) {
                                res.different_room_types.forEach(function(user) {
                                    differentRoomTypesHtml +=
                                        `<li><strong>${user.name}</strong> (${user.type}) - ${user.room_type}</li>`;
                                });
                            }
                            differentRoomTypesHtml += '</ul>';

                            Swal.fire({
                                title: '{{ __db('room_type_mismatch') }}',
                                html: `
                            <div class="text-left">
                                <p class="mb-3 text-red-600 font-semibold">${res.message}</p>
                                <div class="bg-gray-100 p-3 rounded mb-3">
                                    <p><strong>{{ __db('hotel') }}:</strong> ${res.hotel_name}</p>
                                    <p><strong>{{ __db('room_number') }}:</strong> ${res.room_number}</p>
                                    <p><strong>{{ __db('requested_room_type') }}:</strong> ${res.requested_room_type}</p>
                                </div>
                                <p class="font-semibold mb-1">{{ __db('current_assignments_with_different_types') }}:</p>
                                ${differentRoomTypesHtml}
                                <p class="mt-3 text-sm text-gray-600 font-semibold">{{ __db('please_select_different_room_or_matching_type') }}</p>
                            </div>
                        `,
                                icon: 'error',
                                confirmButtonColor: '#B68A35',
                                confirmButtonText: '{{ __db('ok') }}',
                                customClass: {
                                    popup: 'w-full max-w-2xl',
                                    confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]'
                                }
                            });
                        }
                        checkboxDel.prop('checked', false);

                        if ($('#hotelDelegate').val() == hotelId) {
                            hotelData(hotelId, 'Delegate');
                        }
                    });
                });

                // Escort Section
                $(document).on('change', '#hotelEscort', function() {
                    $('.assign-hotel-checkbox-escort').prop('checked', false);

                    let hotelId = this.value;
                    hotelData(hotelId, 'Escort');
                });

                $('.assign-hotel-checkbox-escort').on('change', function() {
                    let hotelIdEscort = $('#hotelEscort').val();
                    let HotelnameEscort = $('#hotelEscort option:selected').text();

                    if (!hotelIdEscort) {
                        this.checked = false;
                        toastr.error('{{ __db('please_select_hotel') }}');
                        return;
                    }

                    let rowEscort = $(this).closest('tr');
                    let escortId = rowEscort.data('id');
                    let dropdownEscort = rowEscort.find('.room-type-dropdown-escort');
                    let hotel_nameEscort = rowEscort.find('.hotel_name_escort');

                    if (this.checked) {
                        let url = "{{ route('accommodation.rooms', ':id') }}";
                        url = url.replace(':id', hotelIdEscort);

                        $.get(url, function(data) {
                            dropdownEscort.empty().append(
                                '<option value="">{{ __db('select') }}</option>');
                            data.forEach(function(room) {
                                let availableEscort = room.total_rooms - room.assigned_rooms;
                                if (availableEscort > 0) {
                                    dropdownEscort.append('<option value="' + room.id + '">' +
                                        room.room_type?.value + '</option>');
                                }
                            });
                        });

                        hotel_nameEscort.text(HotelnameEscort);
                        $('#hotel_id_escort' + escortId).val(hotelIdEscort);
                    } else {
                        dropdownEscort.empty().append('<option value="">{{ __db('select') }}</option>');
                    }
                });

                $('.save-room-assignment-escort').on('click', function(e) {
                    e.preventDefault();
                    let escortrow = $(this).closest('tr');
                    let checkboxEscort = escortrow.find('input[type="checkbox"]');
                    let idEscort = escortrow.data('id');
                    let hotelIdEscort = $('#hotel_id_escort' + idEscort).val();
                    let roomTypeIdEscort = escortrow.find('.room-type-dropdown-escort').val();
                    let roomNumberEscort = escortrow.find('.room-number-input-escort').val();

                    if (!hotelIdEscort || !roomTypeIdEscort) {
                        toastr.error('{{ __db('please_select_room_details') }}');
                        return;
                    }

                    $.post("{{ route('accommodation.assign-rooms') }}", {
                        _token: '{{ csrf_token() }}',
                        assignable_id: idEscort,
                        assignable_type: 'Escort',
                        hotel_id: hotelIdEscort,
                        room_type_id: roomTypeIdEscort,
                        room_number: roomNumberEscort,
                        delegation_id: {{ $delegation->id }}
                    }, function(res) {
                        if (res.success === 1) {
                            escortrow.css('background-color', '#acf3bc');
                            toastr.success('{{ __db('room_assigned') }}');


                            let actionCellEsc = escortrow.find('td').last();
                            if (actionCellEsc.find('.remove-room-assignment').length === 0) {
                                let removeBtnHtml = `
                                <a href="#" class="remove-room-assignment text-xs bg-red-600 text-white rounded-lg py-1 px-3 ml-2" data-assignment-id="${res.assignment_id}">
                                    {{ __db('remove') }}
                                </a>`;
                                actionCellEsc.append(removeBtnHtml);
                            } else {
                                let actionCellEsc = escortrow.find('td').last();
                                let removeBtn = actionCellEsc.find('.remove-room-assignment')
                                removeBtn.attr('data-assignment-id', res.assignment_id).show();
                            }

                        } else if (res.success === 0) {
                            toastr.success('{{ __db('room_already_assigned') }}');
                        } else if (res.success === 2) {
                            toastr.error('{{ __db('room_not_available') }}');
                        } else if (res.success === 3) {
                            toastr.error('{{ __db('room_already_booked_by_external_member') }}');
                        } else if (res.success === 5) {
                            // Show confirmation dialog when room is already assigned to other users
                            let existingUsersHtml = '';
                            if (res.existing_users && res.existing_users.length > 0) {
                                res.existing_users.forEach(function(user) {
                                    existingUsersHtml +=
                                        `<div>${user.name} (${user.type})</div>`;
                                });
                            } else {
                                existingUsersHtml = '<div>{{ __db('unknown_user') }}</div>';
                            }

                            Swal.fire({
                                title: "{{ __db('confirm_room_assignment') }}",
                                html: `<p>{{ __db('room_already_assigned_to_users') }}:</p>
                                   <div class="text-left bg-gray-100 p-3 rounded mt-2">${existingUsersHtml}</div>
                                   <p class="mt-3"><strong>{{ __db('hotel') }}:</strong> ${res.hotel_name}</p>
                                   <p><strong>{{ __db('room_number') }}:</strong> ${res.room_number}</p>
                                   <p><strong>{{ __db('room_type') }}:</strong> ${res.room_type}</p>
                                   <p class="text-red-600 font-bold mt-3">{{ __db('confirm_proceed_assignment') }}</p>`,
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: "{{ __db('yes_proceed') }}",
                                cancelButtonText: "{{ __db('cancel') }}",
                                customClass: {
                                    popup: 'w-full max-w-2xl',
                                    confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]',
                                    cancelButton: 'px-4 rounded-lg'
                                },
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Proceed with the reassignment by making the same call but with confirmation parameter
                                    $.post("{{ route('accommodation.assign-rooms') }}", {
                                        _token: '{{ csrf_token() }}',
                                        assignable_id: idEscort,
                                        assignable_type: 'Escort',
                                        hotel_id: hotelIdEscort,
                                        room_type_id: roomTypeIdEscort,
                                        room_number: roomNumberEscort,
                                        delegation_id: {{ $delegation->id }},
                                        confirm_duplicate: 1 // Add confirmation flag
                                    }, function(res2) {
                                        if (res2.success === 1) {
                                            escortrow.css('background-color',
                                                '#acf3bc');
                                            toastr.success(
                                                '{{ __db('room_assigned') }}');

                                            let actionCellEsc = escortrow.find('td')
                                                .last();
                                            if (actionCellEsc.find(
                                                    '.remove-room-assignment')
                                                .length === 0) {
                                                let removeBtnHtml = `
                                                <a href="#" class="remove-room-assignment text-xs bg-red-600 text-white rounded-lg py-1 px-3 ml-2" data-assignment-id="${res2.assignment_id}">
                                                    {{ __db('remove') }}
                                                </a>`;
                                                actionCellEsc.append(removeBtnHtml);
                                            } else {
                                                let actionCellEsc = escortrow.find('td')
                                                    .last();
                                                let removeBtn = actionCellEsc.find(
                                                    '.remove-room-assignment')
                                                removeBtn.attr('data-assignment-id',
                                                    res2.assignment_id).show();
                                            }

                                        } else {
                                            // Handle any errors in reassignment
                                            if (res2.success === 2) {
                                                toastr.error(
                                                    '{{ __db('room_not_available') }}'
                                                );
                                            } else if (res2.success === 3) {
                                                toastr.error(
                                                    '{{ __db('room_already_booked_by_external_member') }}'
                                                );
                                            }
                                        }
                                        checkboxEscort.prop('checked', false);
                                        if ($('#hotelEscort').val() == hotelIdEscort) {
                                            hotelData(hotelIdEscort, 'Escort');
                                        }
                                    });
                                }
                            });
                        } else if (res.success == 6) {
                            let differentRoomTypesHtml = '<ul class="list-disc pl-5 mt-2 text-left">';
                            if (res.different_room_types && res.different_room_types.length > 0) {
                                res.different_room_types.forEach(function(user) {
                                    differentRoomTypesHtml +=
                                        `<li><strong>${user.name}</strong> (${user.type}) - ${user.room_type}</li>`;
                                });
                            }
                            differentRoomTypesHtml += '</ul>';

                            Swal.fire({
                                title: '{{ __db('room_type_mismatch') }}',
                                html: `
                                <div class="text-left">
                                    <p class="mb-3 text-red-600 font-semibold">${res.message}</p>
                                    <div class="bg-gray-100 p-3 rounded mb-3">
                                        <p><strong>{{ __db('hotel') }}:</strong> ${res.hotel_name}</p>
                                        <p><strong>{{ __db('room_number') }}:</strong> ${res.room_number}</p>
                                        <p><strong>{{ __db('requested_room_type') }}:</strong> ${res.requested_room_type}</p>
                                    </div>
                                    <p class="font-semibold mb-1">{{ __db('current_assignments_with_different_types') }}:</p>
                                    ${differentRoomTypesHtml}
                                    <p class="mt-3 text-sm text-gray-600 font-semibold">{{ __db('please_select_different_room_or_matching_type') }}</p>
                                </div>
                            `,
                                icon: 'error',
                                confirmButtonColor: '#B68A35',
                                confirmButtonText: '{{ __db('ok') }}',
                                customClass: {
                                    popup: 'w-full max-w-2xl',
                                    confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]'
                                }
                            });
                        }
                        checkboxEscort.prop('checked', false);
                        if ($('#hotelEscort').val() == hotelIdEscort) {
                            hotelData(hotelIdEscort, 'Escort');
                        }
                    });
                });

                // Drivers Section
                $(document).on('change', '#hotelDriver', function() {
                    $('.assign-hotel-checkbox-driver').prop('checked', false);

                    let hotelId = this.value;
                    hotelData(hotelId, 'Driver');
                });

                $('.assign-hotel-checkbox-driver').on('change', function() {
                    let hotelIdDriver = $('#hotelDriver').val();
                    let HotelnameDriver = $('#hotelDriver option:selected').text();

                    if (!hotelIdDriver) {
                        this.checked = false;
                        toastr.error('{{ __db('please_select_hotel') }}');
                        return;
                    }

                    let rowDriver = $(this).closest('tr');
                    let driverId = rowDriver.data('id');
                    let dropdownDriver = rowDriver.find('.room-type-dropdown-driver');
                    let hotel_nameDriver = rowDriver.find('.hotel_name_driver');

                    if (this.checked) {
                        let url = "{{ route('accommodation.rooms', ':id') }}";
                        url = url.replace(':id', hotelIdDriver);

                        $.get(url, function(data) {
                            dropdownDriver.empty().append(
                                '<option value="">{{ __db('select') }}</option>');
                            data.forEach(function(room) {
                                let availableDriver = room.total_rooms - room.assigned_rooms;
                                if (availableDriver > 0) {
                                    dropdownDriver.append('<option value="' + room.id + '">' +
                                        room.room_type?.value + '</option>');
                                }
                            });
                        });

                        hotel_nameDriver.text(HotelnameDriver);
                        $('#hotel_id_driver' + driverId).val(hotelIdDriver);
                    } else {
                        dropdownDriver.empty().append('<option value="">{{ __db('select') }}</option>');
                    }
                });

                $('.save-room-assignment-driver').on('click', function(e) {
                    e.preventDefault();

                    let driverrow = $(this).closest('tr');
                    let checkboxDriver = driverrow.find('input[type="checkbox"]');
                    let idDriver = driverrow.data('id');
                    let hotelIdDriver = $('#hotel_id_driver' + idDriver).val();
                    let roomTypeIdDriver = driverrow.find('.room-type-dropdown-driver').val();
                    let roomNumberDriver = driverrow.find('.room-number-input-driver').val();

                    if (!hotelIdDriver || !roomTypeIdDriver) {
                        toastr.error('{{ __db('please_select_room_details') }}');
                        return;
                    }

                    $.post("{{ route('accommodation.assign-rooms') }}", {
                        _token: '{{ csrf_token() }}',
                        assignable_id: idDriver,
                        assignable_type: 'Driver',
                        hotel_id: hotelIdDriver,
                        room_type_id: roomTypeIdDriver,
                        room_number: roomNumberDriver,
                        delegation_id: {{ $delegation->id }}
                    }, function(res) {
                        if (res.success === 1) {
                            driverrow.css('background-color', '#acf3bc');
                            toastr.success('{{ __db('room_assigned') }}');

                            let actionCell = driverrow.find('td')
                                .last(); // assuming last cell is action
                            if (actionCell.find('.remove-room-assignment').length === 0) {
                                let removeBtnHtml = `
                                <a href="#" class="remove-room-assignment text-xs bg-red-600 text-white rounded-lg py-1 px-3 ml-2" data-driver-id="${idDriver}" data-assignment-id="${res.assignment_id}">
                                    {{ __db('remove') }}
                                </a>`;
                                actionCell.append(removeBtnHtml);
                            } else {
                                let actionCell = driverrow.find('td').last();
                                let removeBtn = actionCell.find('.remove-room-assignment')
                                removeBtn.attr('data-assignment-id', res.assignment_id).show();
                            }
                        } else if (res.success === 0) {
                            toastr.success('{{ __db('room_already_assigned') }}');
                        } else if (res.success === 2) {
                            toastr.error('{{ __db('room_not_available') }}');
                        } else if (res.success === 3) {
                            toastr.error('{{ __db('room_already_booked_by_external_member') }}');
                        } else if (res.success === 5) {
                            let existingUsersHtml = '';
                            if (res.existing_users && res.existing_users.length > 0) {
                                res.existing_users.forEach(function(user) {
                                    existingUsersHtml +=
                                        `<div>${user.name} (${user.type})</div>`;
                                });
                            } else {
                                existingUsersHtml = '<div>{{ __db('unknown_user') }}</div>';
                            }

                            Swal.fire({
                                title: "{{ __db('confirm_room_assignment') }}",
                                html: `<p>{{ __db('room_already_assigned_to_users') }}:</p>
                                   <div class="text-left bg-gray-100 p-3 rounded mt-2">${existingUsersHtml}</div>
                                   <p class="mt-3"><strong>{{ __db('hotel') }}:</strong> ${res.hotel_name}</p>
                                   <p><strong>{{ __db('room_number') }}:</strong> ${res.room_number}</p>
                                   <p><strong>{{ __db('room_type') }}:</strong> ${res.room_type}</p>
                                   <p class="text-red-600 font-bold mt-3">{{ __db('confirm_proceed_assignment') }}</p>`,
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: "{{ __db('yes_proceed') }}",
                                cancelButtonText: "{{ __db('cancel') }}",
                                customClass: {
                                    popup: 'w-full max-w-2xl',
                                    confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]',
                                    cancelButton: 'px-4 rounded-lg'
                                },
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.post("{{ route('accommodation.assign-rooms') }}", {
                                        _token: '{{ csrf_token() }}',
                                        assignable_id: idDriver,
                                        assignable_type: 'Driver',
                                        hotel_id: hotelIdDriver,
                                        room_type_id: roomTypeIdDriver,
                                        room_number: roomNumberDriver,
                                        delegation_id: {{ $delegation->id }},
                                        confirm_duplicate: 1
                                    }, function(res2) {
                                        if (res2.success === 1) {
                                            driverrow.css('background-color',
                                                '#acf3bc');
                                            toastr.success(
                                                '{{ __db('room_assigned') }}');

                                            let actionCell = driverrow.find('td')
                                                .last();
                                            if (actionCell.find(
                                                    '.remove-room-assignment')
                                                .length === 0) {
                                                let removeBtnHtml = `
                                                <a href="#" class="remove-room-assignment text-xs bg-red-600 text-white rounded-lg py-1 px-3 ml-2" data-driver-id="${idDriver}" data-assignment-id="${res2.assignment_id}">
                                                    {{ __db('remove') }}
                                                </a>`;
                                                actionCell.append(removeBtnHtml);
                                            } else {
                                                let actionCell = driverrow.find('td')
                                                    .last();
                                                let removeBtn = actionCell.find(
                                                    '.remove-room-assignment')
                                                removeBtn.attr('data-assignment-id',
                                                    res2.assignment_id).show();
                                            }
                                        } else {
                                            // Handle any errors in reassignment
                                            if (res2.success === 2) {
                                                toastr.error(
                                                    '{{ __db('room_not_available') }}'
                                                );
                                            } else if (res2.success === 3) {
                                                toastr.error(
                                                    '{{ __db('room_already_booked_by_external_member') }}'
                                                );
                                            }
                                        }
                                        checkboxDriver.prop('checked', false);

                                        if ($('#hotelDriver').val() == hotelIdDriver) {
                                            hotelData(hotelIdDriver, 'Driver');
                                        }
                                    });
                                }
                            });
                        } else if (res.success == 6) {
                            let differentRoomTypesHtml = '<ul class="list-disc pl-5 mt-2 text-left">';
                            if (res.different_room_types && res.different_room_types.length > 0) {
                                res.different_room_types.forEach(function(user) {
                                    differentRoomTypesHtml +=
                                        `<li><strong>${user.name}</strong> (${user.type}) - ${user.room_type}</li>`;
                                });
                            }
                            differentRoomTypesHtml += '</ul>';

                            Swal.fire({
                                title: '{{ __db('room_type_mismatch') }}',
                                html: `
                            <div class="text-left">
                                <p class="mb-3 text-red-600 font-semibold">${res.message}</p>
                                <div class="bg-gray-100 p-3 rounded mb-3">
                                    <p><strong>{{ __db('hotel') }}:</strong> ${res.hotel_name}</p>
                                    <p><strong>{{ __db('room_number') }}:</strong> ${res.room_number}</p>
                                    <p><strong>{{ __db('requested_room_type') }}:</strong> ${res.requested_room_type}</p>
                                </div>
                                <p class="font-semibold mb-1">{{ __db('current_assignments_with_different_types') }}:</p>
                                ${differentRoomTypesHtml}
                                <p class="mt-3 text-sm text-gray-600 font-semibold">{{ __db('please_select_different_room_or_matching_type') }}</p>
                            </div>
                        `,
                                icon: 'error',
                                confirmButtonColor: '#B68A35',
                                confirmButtonText: '{{ __db('ok') }}',
                                customClass: {
                                    popup: 'w-full max-w-2xl',
                                    confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]'
                                }
                            });
                        }
                        checkboxDriver.prop('checked', false);

                        if ($('#hotelDriver').val() == hotelIdDriver) {
                            hotelData(hotelIdDriver, 'Driver');
                        }
                    });
                });

                $(document).on('click', '.remove-room-assignment', function(e) {
                    e.preventDefault();
                    let assignmentId = $(this).data('assignment-id');

                    Swal.fire({
                        title: "{{ __db('are_you_sure') }}",
                        text: "{{ __db('unassign_confirm_text_accommodation') }}",
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
                            $.post("{{ route('accommodation.remove-rooms') }}", {
                                _token: '{{ csrf_token() }}',
                                assignable_id: assignmentId
                            }, function(res) {
                                if (res.success) {
                                    toastr.success('{{ __db('room_removed') }}');
                                    window.location.reload();
                                } else {
                                    toastr.error('{{ __db('room_remove_failed') }}');
                                }
                            });
                        }
                    });

                });

                $('#select-all-delegates').on('change', function() {
                    const isChecked = $(this).is(':checked');
                    const hotelId = $('#hotelDelegate').val();

                    if (isChecked && !hotelId) {
                        this.checked = false;
                        toastr.error('{{ __db('please_select_hotel') }}');
                        return;
                    }

                    $('.assign-hotel-checkbox').each(function() {
                        if ($(this).is(':checked') !== isChecked) {
                            $(this).prop('checked', isChecked).trigger('change');
                        }
                    });
                });



                $('#select-all-escorts').on('change', function() {
                    const isChecked = $(this).is(':checked');
                    const hotelId = $('#hotelEscort').val();

                    if (isChecked && !hotelId) {
                        this.checked = false;
                        toastr.error('{{ __db('please_select_hotel') }}');
                        return;
                    }

                    $('.assign-hotel-checkbox-escort').each(function() {
                        if ($(this).is(':checked') !== isChecked) {
                            $(this).prop('checked', isChecked).trigger('change');
                        }
                    });
                });

                $('#select-all-drivers').on('change', function() {
                    const isChecked = $(this).is(':checked');
                    const hotelId = $('#hotelDriver').val();

                    if (isChecked && !hotelId) {
                        this.checked = false;
                        toastr.error('{{ __db('please_select_hotel') }}');
                        return;
                    }

                    $('.assign-hotel-checkbox-driver').each(function() {
                        if ($(this).is(':checked') !== isChecked) {
                            $(this).prop('checked', isChecked).trigger('change');
                        }
                    });
                });

                $(document).on('change', '.accommodation-status-checkbox', function() {
                    const checkbox = $(this);
                    const delegateId = checkbox.data('delegate-id');
                    const isChecked = checkbox.is(':checked');
                    const row = checkbox.closest('tr');

                    const hasRoomAssigned = row.find('.remove-room-assignment').length > 0;

                    if (!isChecked && hasRoomAssigned) {
                        toastr.error('Please unassign room first');
                        checkbox.prop('checked', !isChecked);
                        return;
                    }

                    $.ajax({
                        url: "{{ route('accommodation.update-accommodation-status') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            delegate_id: delegateId,
                            accommodation_status: isChecked ? 1 : 0
                        },
                        success: function(response) {
                            if (response.success) {
                                row.removeClass('bg-[#e5e5e5] bg-[#acf3bc]');

                                if (!isChecked) {
                                    row.addClass('bg-[#e5e5e5]');
                                } else {
                                    const hasRoomNow = row.find('.remove-room-assignment').length >
                                        0;
                                    if (hasRoomNow) {
                                        row.addClass('bg-[#acf3bc]');
                                    }
                                }

                                toastr.success(isChecked ?
                                    'Accommodation required' :
                                    'Accommodation not required');

                                const assignHotelCheckbox = row.find('.assign-hotel-checkbox');
                                if (assignHotelCheckbox.length) {
                                    assignHotelCheckbox.prop('disabled', !isChecked);
                                    if (!isChecked) {
                                        assignHotelCheckbox.prop('checked', false);
                                        row.find('.room-type-dropdown').empty().append(
                                            '<option value="">{{ __db('select') }}</option>');
                                        row.find('.room-number-input').val('');
                                        row.find('.hotel_name').text('');
                                        row.find('.hotel-id-input').val('');
                                    }
                                }

                                window.location.reload();
                            } else {
                                toastr.error(response.message ||
                                    'Error updating accommodation status');
                                checkbox.prop('checked', !isChecked);
                            }
                        },
                        error: function(xhr) {
                            console.error('Error updating accommodation status:', xhr);
                            toastr.error('Error updating accommodation status');
                            checkbox.prop('checked', !isChecked);
                        }
                    });
                });

            });
        </script>
    @endsection
