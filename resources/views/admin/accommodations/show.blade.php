@extends('layouts.admin_account', ['title' => $hotel->hotel_name ?? ''])

@section('content')
    <div class="dashboard-main-body">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ $hotel->hotel_name ?? '' }}</h2>
            <a href="{{ session()->get('accommodations_last_url') ? session()->get('accommodations_last_url') : route('accommodations.index') }}"
                class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 12H5m14 0-4 4m4-4-4-4" />
                </svg>
                <span>{{ __db('back') }}</span>
            </a>
        </div>
        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
            <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                <thead>
                    <tr>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('hotel_name') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('address') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('contact_number') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('contact_point') }}
                        </th>

                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('room_type') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('total_capacity') }}
                        </th>
                        @directCanany(['edit_accommodations', 'hotel_edit_accommodations'])
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('action') }}
                            </th>
                        @enddirectCanany
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-xs align-[top]">
                        <td class="px-4 py-3 border border-gray-200">
                            {{ $hotel->hotel_name ?? '' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200">{{ $hotel->address ?? '-' }}</td>
                        <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">
                            {{ $hotel->contact_number ?? '-' }}</td>
                        <td class="px-4 py-3 border border-gray-200">
                            @if ($hotel->contacts)
                                @foreach ($hotel->contacts as $contact_person)
                                    <div class="mb-2">{{ $contact_person->name ?? '-' }} -
                                        {{ $contact_person->phone ?? '-' }}</div>
                                @endforeach
                            @endif
                        </td>

                        <td class="px-4 py-3 border border-gray-200">
                            @php
                                $total_rooms = 0;
                                $assigned_rooms = 0;
                            @endphp
                            @if ($hotel->rooms)
                                @foreach ($hotel->rooms as $room)
                                    <div class="mb-2">{{ $room->roomType?->value ?? '-' }} -
                                        {{ $room->assigned_rooms }}/{{ $room->total_rooms }}</div>
                                    @php
                                        $total_rooms += $room->total_rooms;
                                        $assigned_rooms += $room->assigned_rooms;
                                    @endphp
                                @endforeach
                            @endif
                        </td>
                        <td class="px-4 py-3 border border-gray-200">{{ $assigned_rooms }}/{{ $total_rooms }}</td>
                        @directCanany(['edit_accommodations', 'hotel_edit_accommodations'])
                            <td class="px-4 py-2 border border-gray-200">
                                <div class="flex align-center gap-4">

                                    <a href="{{ route('accommodations.edit', ['id' => base64_encode($hotel->id)]) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>


                                </div>
                            </td>
                        @enddirectCanany
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white p-4 mt-4">
            <form id="filterForm" method="GET" action="{{ route('accommodations.show', base64_encode($hotel->id)) }}"
                class="mb-4">
                <div class="grid grid-cols-12 gap-5 room-row">
                    <div class="col-span-3">
                        <label class="form-label">{{ __db('delegations') }}</label>
                        <select name="delegation_id" id="delegation_id"
                            class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                            <option value="">{{ __db('select') }}</option>
                            @foreach ($delegations as $delegation)
                                <option value="{{ $delegation->id }}"
                                    {{ request('delegation_id') == $delegation->id ? 'selected' : '' }}>
                                    {{ $delegation->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-3">
                        <label class="form-label">{{ __db('room_type') }}</label>
                        <select name="room_type" id="room_type"
                            class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0"
                            data-placeholder="{{ __db('select') }}">
                            <option value="">{{ __db('select') }}</option>
                            @foreach ($rooms as $roomType)
                                <option value="{{ $roomType->id }}"
                                    {{ request('room_type') == $roomType->id ? 'selected' : '' }}>
                                    {{ $roomType->roomType?->value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <hr class=" border-gray-300 my-5">

        <!-- Hidden Template Row -->
        <div class="flex items-center justify-between">
            <h2 class="font-semibold mb-0 text-[18px] ">{{ __db('delegates') }} ({{ $delegates->count() }})</h2>
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
                                'label' => __db('country'),
                                'render' => fn($row) => e($row->delegation?->country->name ?? ''),
                            ],

                            [
                                'label' => __db('invitation_from'),
                                'render' => fn($row) => $row->delegation->invitationFrom->value ?? '-',
                            ],

                            [
                                'label' => __db('delegation'),
                                'render' => fn($row) => e($row->delegation?->code ?? ''),
                            ],
                            [
                                'label' => __db('name_en'),
                                'render' => function ($row) {
                                    $teamHeadBadge = $row->assignable?->team_head
                                        ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span>'
                                        : '';
                                    $name = $row->assignable?->name_en ?: '';
                                    return $teamHeadBadge .
                                        '<div class="block">' .
                                        $row->assignable?->title_en .
                                        ' ' .
                                        e($name) .
                                        '</div>';
                                },
                            ],
                            [
                                'label' => __db('name_ar'),
                                'render' => function ($row) {
                                    $teamHeadBadge = $row->assignable?->team_head
                                        ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span>'
                                        : '';
                                    $name = $row->assignable?->name_ar ?: '';
                                    return $teamHeadBadge . '<div class="block">' . e($name) . '</div>';
                                },
                            ],
                            [
                                'label' => __db('designation_en'),
                                'render' => fn($row) => $row->assignable?->designation_en ?:
                                $row->assignable?->designation_ar ?:
                                '-',
                            ],
                            [
                                'label' => __db('designation_ar'),
                                'render' => fn($row) => $row->assignable?->designation_ar ?: '-',
                            ],
                            [
                                'label' => __db('internal_ranking'),
                                'render' => fn($row) => $row->assignable?->internalRanking?->value ?? '-',
                            ],
                            [
                                'label' => __db('gender'),
                                'render' => fn($row) => $row->assignable?->gender?->value ?? '-',
                            ],
                            [
                                'label' => __db('parent_id'),
                                'render' => fn($row) => $row->assignable?->parent
                                    ? ($row->assignable?->parent->name_en ?:
                                    $row->assignable?->parent->name_ar ?:
                                    '-')
                                    : '-',
                            ],
                            [
                                'label' => __db('relationship'),
                                'render' => fn($row) => $row->assignable?->relationship?->value ?? '-',
                            ],
                            [
                                'label' => __db('badge_printed'),
                                'render' => fn($row) => $row->assignable?->badge_printed ? 'Yes' : 'No',
                            ],
                            [
                                'label' => __db('participation_status'),
                                'render' => function ($row) {
                                    return $row->assignable?->participation_status ?? '-';
                                },
                            ],
                            [
                                'label' => __db('accommodation'),
                                'render' => function ($row) {
                                    if (!$row->assignable?->accommodation) {
                                        return 'Not Required';
                                    }

                                    $room = $row->assignable?->currentRoomAssignment ?? null;

                                    $accommodation = $row->assignable?->current_room_assignment_id
                                        ? $room->roomType?->roomType?->value . ' - ' . $room?->room_number ?? '-'
                                        : '-';

                                    return $accommodation ?? '-';
                                },
                            ],
                            [
                                'label' => __db('arrival_status'),
                                'render' => function ($row) {
                                    $id = $row->assignable?->id ?? uniqid();
                                    return '<svg class="cursor-pointer" width="36" height="30" data-modal-target="delegate-transport-modal-' .
                                        $id .
                                        '" data-modal-toggle="delegate-transport-modal-' .
                                        $id .
                                        '" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#B68A35"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><rect width="480" height="32" x="16" y="464" fill="var(--ci-primary-color, #B68A35)" class="ci-primary"></rect><path fill="var(--ci-primary-color, #B68A35)" d="M455.688,152.164c-23.388-6.515-48.252-6.053-70.008,1.3l-.894.3-65.1,30.94L129.705,109.176a47.719,47.719,0,0,0-49.771,8.862L54.5,140.836a24,24,0,0,0,2.145,37.452l117.767,83.458-45.173,23.663L93.464,252.722a48.067,48.067,0,0,0-51.47-8.6l-19.455,8.435a24,24,0,0,0-11.642,33.3L83.718,422.684,480.3,227.21c23.746-11.177,26.641-29.045,21.419-42.059C495.931,170.723,479.151,158.7,455.688,152.164Zm10.9,46.133-.149.07L97.394,380.267l-54.176-101.8,11.5-4.987a16.021,16.021,0,0,1,17.157,2.867l52.336,47.819,111.329-58.318L83.322,157.974l17.971-16.108a15.908,15.908,0,0,1,16.59-2.954l202.943,80.681,75.95-36.095c15.456-5.009,33.863-5.165,50.662-.413,13.834,3.914,21.182,9.6,23.672,12.582A24.211,24.211,0,0,1,466.59,198.3Z" class="ci-primary"></path></g></svg>';
                                },
                            ],
                            [
                                'label' => __db('action'),
                                'permission' => ['assign_accommodations', 'hotel_assign_accommodations'],
                                'render' => function ($row) {
                                    $buttons =
                                        '<a href="' .
                                        route(
                                            'accommodation-delegation-view',
                                            base64_encode($row->delegation?->id ?? uniqid()),
                                        ) .
                                        '" class="">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>';
                                    return $buttons;
                                },
                            ],
                        ];

                        $noDataMessage = __db('no_data_found');
                        $delegates = $delegates ?? collect();
                    @endphp

                    <x-reusable-table :data="$delegates" table-id="delegatesTableEdit" :enableColumnListBtn="true" :columns="$columns"
                        :no-data-message="__db('no_data_found')" />

                    @foreach ($delegates as $delegate)
                        @php
                            $delegate = $delegate->assignable;
                        @endphp
                        <div id="delegate-transport-modal-{{ $delegate->id }}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-2xl mx-auto">
                                <div class="bg-white rounded-lg shadow ">
                                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                                        <h3 class="text-xl font-semibold text-gray-900">
                                            {{ __db('transport_information_for') }}
                                            {{ $delegate->name_en ?? '-' }}</h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 mr-auto inline-flex items-center"
                                            data-modal-hide="delegate-transport-modal-{{ $delegate->id }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="p-6 space-y-6">
                                        <h3 class="text-xl font-semibold text-gray-900 pb-2">{{ __db('arrival') }}</h3>
                                        @php
                                            $arrival = $delegate->delegateTransports->where('type', 'arrival')->first();
                                        @endphp
                                        <div class="border rounded-lg p-6 grid grid-cols-2 gap-x-8">
                                            @if ($arrival)
                                                <div class="border-b py-4">
                                                    <p class="font-medium text-gray-600">{{ __db('to_airport') }}</p>
                                                    <p class="text-base">{{ $arrival->airport?->value ?? '-' }}</p>
                                                </div>
                                                <div class="border-b py-4">
                                                    <p class="font-medium text-gray-600">{{ __db('flight_no') }}</p>
                                                    <p class="text-base">{{ $arrival->flight_no ?? '-' }}</p>
                                                </div>
                                                <div class="py-4 border-b md:border-b-0">
                                                    <p class="font-medium text-gray-600">{{ __db('flight_name') }}</p>
                                                    <p class="text-base">{{ $arrival->flight_name ?? '-' }}</p>
                                                </div>
                                                <div class="py-4 !pb-0">
                                                    <p class="font-medium text-gray-600">{{ __db('date_time') }}</p>
                                                    <p class="text-base">{{ $arrival->date_time ?? '-' }}</p>
                                                </div>
                                            @else
                                                <p class="col-span-2 text-gray-500">No arrival information available.</p>
                                            @endif
                                        </div>

                                        <h3 class="text-xl font-semibold text-gray-900 pb-2">{{ __db('departure') }}</h3>
                                        @php
                                            $departure = $delegate->delegateTransports
                                                ->where('type', 'departure')
                                                ->first();
                                        @endphp
                                        <div class="border rounded-lg p-6 grid grid-cols-2 gap-x-8">
                                            @if ($departure)
                                                <div class="border-b py-4">
                                                    <p class="font-medium text-gray-600">{{ __db('from_airport') }}</p>
                                                    <p class="text-base">{{ $departure->airport?->value ?? '-' }}</p>
                                                </div>
                                                <div class="border-b py-4">
                                                    <p class="font-medium text-gray-600">{{ __db('flight_no') }}</p>
                                                    <p class="text-base">{{ $departure->flight_no ?? '-' }}</p>
                                                </div>
                                                <div class="py-4 border-b md:border-b-0">
                                                    <p class="font-medium text-gray-600">{{ __db('flight_name') }}</p>
                                                    <p class="text-base">{{ $departure->flight_name ?? '-' }}</p>
                                                </div>
                                                <div class="py-4 !pb-0">
                                                    <p class="font-medium text-gray-600">{{ __db('date_time') }}</p>
                                                    <p class="text-base">{{ $departure->date_time ?? '-' }}</p>
                                                </div>
                                            @else
                                                <p class="col-span-2 text-gray-500">No departure information available.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

        <hr class="mx-6 border-neutral-200 h-10">

        <div class="flex items-center justify-between">
            <h2 class="font-semibold mb-0 text-[18px] ">{{ __db('escorts') }} ({{ $escorts->count() }})</h2>
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
                                'label' => __db('delegation'),
                                'key' => 'title',
                                'render' => fn($escort) => e($escort->delegation?->code ?? ''),
                            ],
                            [
                                'label' => __db('escort') . ' ' . __db('code'),
                                'render' => function ($escort) {
                                    $searchUrl = route('escorts.index', ['search' => $escort->assignable?->code]);
                                    return '<a href="' .
                                        $searchUrl .
                                        '" class="text-[#B68A35] hover:underline">' .
                                        e($escort->assignable?->code) .
                                        '</a>';
                                },
                            ],
                            [
                                'label' => __db('military_number'),
                                'key' => 'military_number',
                                'render' => function ($escort) {
                                    $searchUrl = route('escorts.index', [
                                        'search' => $escort->assignable?->military_number,
                                    ]);
                                    return '<a href="' .
                                        $searchUrl .
                                        '" class="text-[#B68A35] hover:underline">' .
                                        e($escort->assignable?->military_number) .
                                        '</a>';
                                },
                            ],

                            [
                                'label' => __db('name_en'),
                                'key' => 'name',
                                'render' => function ($escort) {
                                    $searchUrl = route('escorts.index', ['search' => $escort->assignable?->name_en]);
                                    return '<a href="' .
                                        $searchUrl .
                                        '" class="text-[#B68A35] hover:underline">' .
                                        e($escort->assignable?->title_en ?? '') .
                                        e($escort->assignable?->name_en) .
                                        '</a>';
                                },
                            ],
                            [
                                'label' => __db('phone_number'),
                                'key' => 'phone_number',
                                'render' => fn($escort) => '<span dir="ltr">' .
                                    e($escort->assignable?->phone_number) .
                                    '</span>',
                            ],
                            [
                                'label' => __db('gender'),
                                'key' => 'gender',
                                'render' => fn($escort) => e(optional($escort->assignable?->gender)->value),
                            ],
                            [
                                'label' => __db('spoken_languages'),
                                'key' => 'known_languages',
                                'render' => function ($escort) {
                                    $ids = $escort->assignable?->spoken_languages
                                        ? explode(',', $escort->assignable?->spoken_languages)
                                        : [];
                                    $names = \App\Models\DropdownOption::whereIn('id', $ids)->pluck('value')->toArray();
                                    return e(implode(', ', $names));
                                },
                            ],
                            [
                                'label' => __db('accommodation'),
                                'render' => function ($escort) {
                                    $room = $escort->assignable?->currentRoomAssignment ?? null;

                                    $accommodation = $escort->assignable?->current_room_assignment_id
                                        ? $room->roomType?->roomType?->value . ' - ' . $room?->room_number ?? '-'
                                        : '-';
                                    return $accommodation ?? '-';
                                },
                            ],
                            [
                                'label' => __db('actions'),
                                'key' => 'actions',
                                'permission' => ['assign_accommodations', 'hotel_assign_accommodations'],
                                'render' => function ($escort) {
                                    $buttons =
                                        '<a href="' .
                                        route(
                                            'accommodation-delegation-view',
                                            base64_encode($escort->delegation?->id),
                                        ) .
                                        '" class="">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>';
                                    return $buttons;
                                },
                            ],
                        ];
                    @endphp

                    <x-reusable-table :data="$escorts" tableId="escortsTable" :columns="$columns" :no-data-message="__db('no_data_found')" />
                </div>
            </div>
        </div>

        <hr class="mx-6 border-neutral-200 h-10">

        <div class="flex items-center justify-between">
            <h2 class="font-semibold mb-0 text-[18px] ">{{ __db('drivers') }} ({{ $drivers->count() }})</h2>
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
                                'label' => __db('delegation'),
                                'render' => fn($driver) => e($driver->delegation?->code ?? ''),
                            ],
                            [
                                'label' => __db('driver') . ' ' . __db('code'),
                                'render' => function ($driver) {
                                    $searchUrl = route('drivers.index', ['search' => $driver->assignable?->code]);
                                    return '<a href="' .
                                        $searchUrl .
                                        '" class="text-[#B68A35] hover:underline">' .
                                        e($driver->assignable?->code) .
                                        '</a>';
                                },
                            ],
                            [
                                'label' => __db('military_number'),
                                'key' => 'military_number',
                                'render' => function ($driver) {
                                    $searchUrl = route('drivers.index', [
                                        'search' => $driver->assignable?->military_number,
                                    ]);
                                    return '<a href="' .
                                        $searchUrl .
                                        '" class="text-[#B68A35] hover:underline">' .
                                        e($driver->assignable?->military_number) .
                                        '</a>';
                                },
                            ],

                            [
                                'label' => __db('name_en'),
                                'key' => 'name_en',
                                'render' => function ($driver) {
                                    $searchUrl = route('drivers.index', ['search' => $driver->assignable?->name_en]);
                                    return '<a href="' .
                                        $searchUrl .
                                        '" class="text-[#B68A35] hover:underline">' .
                                        e($driver->assignable?->title_en ?? '') .
                                        e($driver->assignable?->name_en) .
                                        '</a>';
                                },
                            ],
                            [
                                'label' => __db('phone_number'),
                                'key' => 'phone_number',
                                'render' => fn($driver) => '<span dir="ltr">' .
                                    e($driver->assignable?->phone_number) .
                                    '</span>',
                            ],
                            [
                                'label' => __db('car') . ' ' . __db('type'),
                                'key' => 'car_type',
                                'render' => fn($driver) => e($driver->assignable?->car_type),
                            ],
                            [
                                'label' => __db('car') . ' ' . __db('number'),
                                'key' => 'car_number',
                                'render' => fn($driver) => e($driver->assignable?->car_number),
                            ],
                            [
                                'label' => __db('capacity'),
                                'key' => 'capacity',
                                'render' => fn($driver) => e($driver->assignable?->capacity),
                            ],
                            [
                                'label' => __db('accommodation'),
                                'render' => function ($driver) {
                                    $room = $driver->assignable?->currentRoomAssignment ?? null;

                                    $accommodation = $driver->assignable?->current_room_assignment_id
                                        ? $room->roomType?->roomType?->value . ' - ' . $room?->room_number ?? '-'
                                        : '-';
                                    //;
                                    return $accommodation ?? '-';
                                },
                            ],
                            [
                                'label' => __db('actions'),
                                'key' => 'actions',
                                'permission' => ['assign_accommodations', 'hotel_assign_accommodations'],
                                'render' => function ($driver) {
                                    $buttons =
                                        '<a href="' .
                                        route(
                                            'accommodation-delegation-view',
                                            base64_encode($driver->delegation?->id),
                                        ) .
                                        '" class="">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>';
                                    return $buttons;
                                },
                            ],
                        ];
                    @endphp

                    <x-reusable-table :data="$drivers" tableId="driversTable" :columns="$columns" :no-data-message="__db('no_data_found')" />
                </div>
            </div>
        </div>

        @directCanany(['edit_accommodations', 'hotel_edit_accommodations'])
            <hr class="mx-6 border-neutral-200 h-10">

            <div class="flex items-center justify-between">
                <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('external_accommodations') }}
                    ({{ $externalMembers->count() }})</h2>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
                <div class="xl:col-span-12 h-full">
                    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                        @php
                            $columns = [
                                [
                                    'label' => __db('sl_no'),
                                    'render' => fn($external, $key) => $key + 1,
                                ],
                                [
                                    'label' => __db('name'),
                                    'key' => 'title',
                                    'render' => fn($external) => e($external->name ?? ''),
                                ],

                                [
                                    'label' => __db('accommodation'),
                                    'render' => function ($external) {
                                        $accommodation =
                                            $external->roomType?->roomType?->value . ' - ' . $external?->room_number;
                                        //;
                                        return $accommodation ?? '-';
                                    },
                                ],
                                [
                                    'label' => __db('actions'),
                                    'key' => 'actions',
                                    'permission' => ['assign_external_members', 'hotel_assign_external_members'],
                                    'render' => function ($external) {
                                        $buttons =
                                            '<a href="' .
                                            route('external-members.edit', $external->id) .
                                            '" class="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>';
                                        return $buttons;
                                    },
                                ],
                            ];
                        @endphp

                        <x-reusable-table :data="$externalMembers" tableId="externalMembersTable" :columns="$columns"
                            :no-data-message="__db('no_data_found')" />
                    </div>
                </div>
            </div>
        @enddirectCanany
    </div>

@endsection

@section('style')
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');

            filterForm.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', function() {
                    filterForm.submit();
                });
            });

            $('.select2').on('select2:select select2:unselect', function() {
                filterForm.submit();
            });
        });
    </script>
@endsection
