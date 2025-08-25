<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-10">
        <h2 class="font-semibold text-2xl">{{ __db('delegation') }}</h2>
        <div class="flex gap-3 ms-auto">

            <x-back-btn class="" back-url="{{ getRouteForPage('accommodation-delegations') }}" />
        </div>
    </div>

    @php
    $columns = [
    ['label' => __db('delegation_id'), 'render' => fn($row) => $row->code ?? '-'],
    ['label' => __db('invitation_from'), 'render' => fn($row) => $row->invitationFrom->value ?? '-'],
    ['label' => __db('continent'), 'render' => fn($row) => $row->continent->value ?? '-'],
    ['label' => __db('country'), 'render' => fn($row) => $row->country->value ?? '-'],
    ['label' => __db('invitation_status'), 'render' => fn($row) => $row->invitationStatus->value ?? '-'],
    ['label' => __db('participation_status'), 'render' => fn($row) => $row->participationStatus->value ?? '-'],
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
        <h2 class="font-semibold mb-0 text-[18px] ">{{ __db('delegates') }} ({{ $delegation->delegates->count() }})</h2>
        <div class="flex items-center gap-3 ">
            <a href="#" id="add-attachment-btn"
                class="text-sm !bg-[#B68A35] w-xs text-center text-white rounded-lg py-3 px-5">
                <span>{{ __db('assign_hotel') }} </span>
            </a>

            <select id="hotelSelect" class="p-3 rounded-lg w-[300px] text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                <option selected disabled>{{ __db('select_hotel') }}</option>
                
            </select>
        </div>

    </div>

    <div id="HotelInfo" style="display: none;">
        <!-- Your hotel information goes here -->
        <div class="flex items-center justify-end gap-3 bg-white p-4 w-auto rounded-lg border border-neutral-200 mt-3">
            <div>
                <h4> <span class="font-semibold">No. of rooms available :</span> 15</h4>
            </div>
            <div class="border-r border-neutral-300 pr-3">
                <h4> <span class="font-semibold">Room Type :</span> Single - Double - Suite</h4>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <table class="table-auto mb-0 border-collapse border border-gray-300 w-full">
                    <thead>
                        <tr class="text-[9px]">
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                <input type="checkbox"
                                    class="w-4 h-4 accent-[#B68A35] border border-white bg-transparent rounded focus:ring-white" />
                            </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('sl_no') }}</th>
                            <th scope="col" class="p-3  !bg-[#B68A35] text-start text-white border !border-[#cbac71] ">
                                {{ __db('title') }}</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('name') }}
                            </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('designation') }}</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('internal_ranking') }}</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('gender') }}</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('parent_id') }}</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('relationship') }}</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('badge_printed') }}</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{  __db('participation_status') }}</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('accommodation') }}</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('arrival_status') }} </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('action') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($delegation->delegates as $key => $row)
                            @php
                                $id = $row->id ?? uniqid();
                                $badge = $row->team_head ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span> ' : '';
                                $arrival = $row->delegateTransports->where('type', 'arrival')->first();
                                $departure = $row->delegateTransports->where('type', 'departure')->first();
                                $departureStatus = $departure && $departure->status ? $departure->status->value : null;
                                $arrivalStatus = $arrival && $arrival->status ? $arrival->status->value : null;
                            @endphp

                            <tr class="text-[12px] align-[middle] @if($row->accommodation == 0) bg-[#e5e5e5] @endif">
                                
                                <td class="px-4 py-2 border border-gray-200">
                                    @if($row->accommodation === 1)
                                        <input type="checkbox" class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded">
                                    @endif
                                </td>
                                <td class="px-4 py-2 border border-gray-200">{{ $key + 1 }}</td>
                                <td class="px-4 border border-gray-200 py-3">{{ $row->title->value }}</td>
                                <td class="px-4 border border-gray-200 py-3">
                                    
                                    {!! $badge !!}
                                    <div class="block">{{ $row->name_en ?? ($row->name_ar ?? '-') }}</div>
                                </td>
                                <td class="px-4 border border-gray-200 py-3">
                                    {{ $row->designation_en ?? ($row->designation_ar ?? '-') }}
                                </td>
                                <td class="px-4 border border-gray-200 py-3">
                                    {{ $row->internalRanking->value ?? '-' }}
                                </td>
                                <td class="px-4 border border-gray-200 py-3">
                                    {{ $row->gender->value ?? '-' }}
                                </td>
                                <td class="px-4 border border-gray-200 py-3">
                                    {{ $row->parent->name_en ?? ($row->parent->name_ar ?? '-') }}
                                </td>
                                <td class="px-4 border border-gray-200 py-3">
                                    {{ $row->relationship->value ?? '-' }}
                                </td>
                                <td class="px-4 border border-gray-200 py-3">
                                    {{ $row->badge_printed ? __db('yes') : __db('no') }}
                                </td>
                                <td class="px-4 border border-gray-200 py-3">
                                    {{ $arrival->status?->value ?? '-' }}
                                </td>
                                <td class="px-4 border border-gray-200 py-3">
                                    -
                                </td>
                                <td class="px-4 border border-gray-200 py-2">
                                    <svg class=" cursor-pointer" width="30" height="30"  data-modal-target="delegate-transport-modal-{{ $id }}" data-modal-toggle="delegate-transport-modal-{{ $id }}" viewBox="0 0 512 512"
                                        xmlns="http://www.w3.org/2000/svg" fill="#B68A35">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <rect width="480" height="32" x="16" y="464"
                                                fill="var(--ci-primary-color, #B68A35)" class="ci-primary"></rect>
                                            <path fill="var(--ci-primary-color, #B68A35)"
                                                d="M455.688,152.164c-23.388-6.515-48.252-6.053-70.008,1.3l-.894.3-65.1,30.94L129.705,109.176a47.719,47.719,0,0,0-49.771,8.862L54.5,140.836a24,24,0,0,0,2.145,37.452l117.767,83.458-45.173,23.663L93.464,252.722a48.067,48.067,0,0,0-51.47-8.6l-19.455,8.435a24,24,0,0,0-11.642,33.3L83.718,422.684,480.3,227.21c23.746-11.177,26.641-29.045,21.419-42.059C495.931,170.723,479.151,158.7,455.688,152.164Zm10.9,46.133-.149.07L97.394,380.267l-54.176-101.8,11.5-4.987a16.021,16.021,0,0,1,17.157,2.867l52.336,47.819,111.329-58.318L83.322,157.974l17.971-16.108a15.908,15.908,0,0,1,16.59-2.954l202.943,80.681,75.95-36.095c15.456-5.009,33.863-5.165,50.662-.413,13.834,3.914,21.182,9.6,23.672,12.582A24.211,24.211,0,0,1,466.59,198.3Z"
                                                class="ci-primary"></path>
                                        </g>
                                    </svg>
                                </td>
                                <td class="px-4 py-3 border border-gray-200">
                                    @if($row->accommodation === 1)
                                        <div class="flex items-center gap-5">
                                            <a href="#" data-modal-target="delegate-transport-modal-{{ $id }}" data-modal-toggle="delegate-transport-modal-{{ $id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 512 512">
                                                    <path
                                                        d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                        fill="#B68A35"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td class="px-4 py-3 text-center " colspan="14" dir="ltr">
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



    @foreach ($delegation->delegates as $delegate)
        <div id="delegate-transport-modal-{{ $delegate->id }}" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow ">
                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">{{ __db('transport_information_for') }}
                            {{ $delegate->name_en ?? '-' }}</h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
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
                                <p class="text-base">{{ $arrival->airport->value ?? '-' }}</p>
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
                        $departure = $delegate->delegateTransports->where('type', 'departure')->first();
                        @endphp
                        <div class="border rounded-lg p-6 grid grid-cols-2 gap-x-8">
                            {{-- ✅ CORRECTED BLOCK --}}
                            @if ($departure)
                            <div class="border-b py-4">
                                <p class="font-medium text-gray-600">{{ __db('from_airport') }}</p>
                                <p class="text-base">{{ $departure->airport->value ?? '-' }}</p>
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

@section('script')
<script>


</script>
@endsection