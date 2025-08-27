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
    ['label' => __db('invitation_from'), 'render' => fn($row) => $row->invitationFrom?->value ?? '-'],
    ['label' => __db('continent'), 'render' => fn($row) => $row->continent?->value ?? '-'],
    ['label' => __db('country'), 'render' => fn($row) => $row->country?->value ?? '-'],
    ['label' => __db('invitation_status'), 'render' => fn($row) => $row->invitationStatus?->value ?? '-'],
    ['label' => __db('participation_status'), 'render' => fn($row) => $row->participationStatus?->value ?? '-'],
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
        @canany(['assign_accommodations', 'hotel_assign_accommodations'])
        <div class="items-center gap-3 ">
            <select id="hotelDelegate" name="hotelDelegate"
                class="select2 p-3 rounded-lg w-[300px] text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                <option selected value="">{{ __db('select_hotel') }}</option>
                @foreach ($hotels as $hot)
                <option value="{{ $hot->id }}">{{ $hot->hotel_name }}</option>
                @endforeach
            </select>
        </div>
        @endcanany
    </div>

    <div id="HotelInfo" style="display: none;">
        <div class="bg-white p-4 w-auto rounded-lg border border-neutral-200 mt-3">
            <h4 class="font-semibold mb-3">{{ __db('room_details') }}</h4>
            <table class="min-w-full text-sm border border-neutral-300 rounded-lg">
                <thead class="bg-neutral-100">
                    <tr>
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
                <table class="table-auto mb-0 border-collapse border border-gray-300 w-full">
                    <thead>
                        <tr class="text-[12px]">
                            @canany(['assign_accommodations', 'hotel_assign_accommodations'])
                            <th scope="col"
                                class="p-2 w-[30px] !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{-- <input type="checkbox"
                                    class="w-4 h-4 accent-[#B68A35] border border-white bg-transparent rounded focus:ring-white" />
                                --}}
                            </th>
                            @endcanany
                            <th scope="col"
                                class="p-2 !bg-[#B68A35] w-[30px] text-start text-white border !border-[#cbac71]">
                                {{ __db('sl_no') }}</th>

                            <th scope="col" class="p-2 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('name') }}
                            </th>
                            <th scope="col" class="p-2 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('designation') }}</th>
                            <th scope="col" class="p-2 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('internal_ranking') }}</th>
                            <th scope="col"
                                class="p-2 w-[50px] !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('gender') }}</th>
                            <th scope="col" class="p-2 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('parent_id') }}</th>

                            <th scope="col"
                                class="p-2 !bg-[#B68A35] w-[100px] text-start text-white border !border-[#cbac71]">
                                {{ __db('participation_status') }}</th>
                            <th scope="col" class="p-2 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('hotel') }}</th>

                            <th scope="col" class="p-2 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('room_type') }}</th>

                            <th scope="col"
                                class="p-2 w-[100px] !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('room_number') }}</th>


                            @canany(['assign_accommodations', 'hotel_assign_accommodations'])
                            <th scope="col"
                                class="p-2 w-[60px] !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('action') }} </th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php
                        echo '
                        <pre>'; print_r($delegation->delegates);die;
                        @endphp --}}
                        @forelse ($delegation->delegates as $key => $row)
                            @php
                                $id = $row->id ?? uniqid();
                                $badge = $row->team_head ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span> ' : '';
                                $arrival = $row->delegateTransports->where('type', 'arrival')->first();
                                $departure = $row->delegateTransports->where('type', 'departure')->first();
                                $departureStatus = $departure && $departure->status ? $departure->status?->value : null;
                                $arrivalStatus = $arrival && $arrival->status ? $arrival->status?->value : null;

                                $room = $row->currentRoomAssignment ?? null;
                               
                            @endphp

                            <tr  data-id="{{ $row->id }}" class="delegate-row text-[11px] align-[middle] @if($row->accommodation == 0) bg-[#e5e5e5] @elseif($room) bg-[#acf3bc] @endif" >
                                @canany(['assign_accommodations', 'hotel_assign_accommodations'])
                                    <td class="px-1 py-2 border border-gray-200">
                                        @if($row->accommodation == 1)
                                            <input type="checkbox" class="assign-hotel-checkbox" data-delegate-id="{{ $row->id }}" class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded">
                                        @endif
                                    </td>
                                @endcanany
                                <td class="px-1 py-2 border border-gray-200">{{ $key + 1 }}</td>
                                <td class="px-1 border border-gray-200 py-3">
                                    
                                    {!! $badge !!} {{ $row->id }}
                                    <div class="block">{{ $row->name_en ?? ($row->name_ar ?? '-') }} {{ $row->title?->value }} </div>
                                </td>
                                <td class="px-1 border border-gray-200 py-3">
                                    {{ $row->designation_en ?? ($row->designation_ar ?? '-') }}
                                </td>
                                <td class="px-1 border border-gray-200 py-3">
                                    {{ $row->internalRanking?->value ?? '-' }}
                                </td>
                                <td class="px-1 border border-gray-200 py-3">
                                    {{ $row->gender?->value ?? '-' }}
                                </td>
                                <td class="px-1 border border-gray-200 py-3">
                                    {{ $row->parent?->name_en ?? ($row->parent?->name_ar ?? '-') }}
                                    <br>
                                    {{ $row->relationship ? '('.$row->relationship->value.')' : '' }}
                                </td>
                               
                                <td class="px-1 border border-gray-200 py-3">
                                    {{ $arrival->status?->value ?? '-' }}
                                </td>
                                <td class="px-1 border border-gray-200 py-3">
                                    <span class="hotel_name">{{ $room?->hotel?->hotel_name ?? '' }}</span>
                                    <input type="hidden" name="hotel_id" id="hotel_id{{ $row->id }}" class="hotel-id-input" data-delegate-id="{{ $row->id }}" value="{{ $room?->hotel_id ?? '' }}">
                                </td>
                                <td class="px-1 border border-gray-200 py-3">

                                    @php 
                                        $options = '';
                                        if($room){
                                            $hotelid = $room->hotel_id;
                                            $roomTypes = App\Models\AccommodationRoom::with('roomType')->where('accommodation_id', $hotelid)->get();
                                            foreach($roomTypes as $roomType){
                                                $options .= '<option value="'.$roomType->id.'" '.($roomType->id == $room->room_type_id ? 'selected' : '').'>'.$roomType->roomType?->value.'</option>';
                                            }
                                        }
                                    @endphp

                                    <select name="room_type" id="room_type" class="room-type-dropdown p-1 rounded-lg w-full text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                        <option value="">{{ __db('select') }}</option>
                                        {!! $options !!}
                                    </select>
                                </td>
                                
                                <td class="px-1 border border-gray-200 py-3">
                                    <input type="text" name="room_number" id="room_number" class="room-number-input w-full p-1 rounded-lg text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ $room?->room_number ?? '' }}">
                                </td>
                                
                                @canany(['assign_accommodations', 'hotel_assign_accommodations'])
                                    <td class="px-1 py-3 border border-gray-200">
                                        @if($row->accommodation == 1)
                                            <div class="flex items-center gap-5">
                                                <a href="#" id="add-attachment-btn" class="save-room-assignment text-xs !bg-[#B68A35] w-xs text-center text-white rounded-lg py-1 px-3">
                                                    <span>{{ __db('save') }} </span>
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                @endcanany
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

    <hr class="mx-6 border-neutral-200 h-2">

    <div class="flex items-center justify-between mt-12">
        <h2 class="font-semibold mb-0 text-[18px] ">{{ __db('escorts') }} ({{ $delegation->escorts->count() }})</h2>
        @canany(['assign_accommodations', 'hotel_assign_accommodations'])
            <div class="items-center gap-3 ">
                <select id="hotelEscort" name="hotelEscort" class="select2 p-3 rounded-lg w-[300px] text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                    <option selected value="">{{ __db('select_hotel') }}</option>
                    @foreach ($hotels as $hot)
                        <option value="{{ $hot->id }}">{{ $hot->hotel_name }}</option>
                    @endforeach
                </select>
            </div>
        @endcanany
    </div>

    <div id="HotelInfoEscort" style="display: none;">
        <div class="bg-white p-4 w-auto rounded-lg border border-neutral-200 mt-3">
            <h4 class="font-semibold mb-3">{{ __db('room_details') }}</h4>
            <table class="min-w-full text-sm border border-neutral-300 rounded-lg">
                <thead class="bg-neutral-100">
                    <tr>
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
                    <tr class="text-[9px]">
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('sl_no') }}</th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('military_number') }}</th>
                        
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('name') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('mobile') }}</th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('gender') }}</th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('hotel') }}</th>
                        <th scope="col" class="p-2 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('room_type') }}</th>

                        <th scope="col" class="p-2 w-[100px] !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('room_number') }}</th>

                        
                        @canany(['assign_accommodations', 'hotel_assign_accommodations'])
                            <th scope="col" class="p-2 w-[60px] !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('action') }} </th>
                        @endcanany
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($delegation->escorts as $keyEscort => $rowEscort)
                        @php
                            $idEscort = $rowEscort->id ?? uniqid();
                            
                            $roomEscort = $rowEscort->currentRoomAssignment ?? null;
                            
                        @endphp

                        <tr  data-id="{{ $rowEscort->id }}" class="escort-row text-[11px] align-[middle] @if($roomEscort) bg-[#acf3bc] @endif" >
                            @canany(['assign_accommodations', 'hotel_assign_accommodations'])
                                <td class="px-1 py-2 border border-gray-200">
                                        <input type="checkbox" class="assign-hotel-checkbox-escort" data-escort-id="{{ $rowEscort->id }}" class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded">
                                </td>
                            @endcanany
                            <td class="px-1 py-2 border border-gray-200">{{ $keyEscort + 1 }}</td>
                            <td class="px-1 border border-gray-200 py-3">
                                <div class="block">{{ $rowEscort->name_en ?? ($rowEscort->name_ar ?? '-') }} {{ $rowEscort->title?->value }} </div>
                            </td>
                            <td class="px-1 border border-gray-200 py-3">
                                {{ $rowEscort->military_number ?? '-' }}
                            </td>
                            <td class="px-1 border border-gray-200 py-3">
                                {{ $rowEscort->phone_number ?? '-' }}
                            </td>
                            <td class="px-1 border border-gray-200 py-3">
                                {{ $rowEscort->gender?->value ?? '-' }}
                            </td>
                            
                            <td class="px-1 border border-gray-200 py-3">
                                <span class="hotel_name_escort">{{ $roomEscort?->hotel?->hotel_name ?? '' }}</span>
                                <input type="hidden" name="hotel_id_escort" id="hotel_id_escort{{ $rowEscort->id }}" class="hotel-id-input-escort" data-escort-id="{{ $rowEscort->id }}" value="{{ $roomEscort?->hotel_id ?? '' }}">
                            </td>
                            <td class="px-1 border border-gray-200 py-3">

                                @php 
                                    $optionsEscort = '';
                                    if($roomEscort){
                                        $hotelidEscort = $roomEscort->hotel_id;
                                        $roomTypesEscort = App\Models\AccommodationRoom::with('roomType')->where('accommodation_id', $hotelidEscort)->get();
                                        foreach($roomTypesEscort as $roomTypeEscort){
                                            $optionsEscort .= '<option value="'.$roomTypeEscort->id.'" '.($roomTypeEscort->id == $roomEscort->room_type_id ? 'selected' : '').'>'.$roomTypeEscort->roomType?->value.'</option>';
                                        }
                                    }
                                @endphp

                                <select name="room_type_escort" id="room_type_escort" class="room-type-dropdown-escort p-1 rounded-lg w-full text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                    <option value="">{{ __db('select') }}</option>
                                    {!! $optionsEscort !!}
                                </select>
                            </td>
                            
                            <td class="px-1 border border-gray-200 py-3">
                                <input type="text" name="room_number_escort" id="room_number_escort" class="room-number-input-escort w-full p-1 rounded-lg text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ $roomEscort?->room_number ?? '' }}">
                            </td>
                            
                            @canany(['assign_accommodations', 'hotel_assign_accommodations'])
                                <td class="px-1 py-3 border border-gray-200">
                                    <div class="flex items-center gap-5">
                                        <a href="#" id="add-attachment-btn" class="save-room-assignment-escort text-xs !bg-[#B68A35] w-xs text-center text-white rounded-lg py-1 px-3">
                                            <span>{{ __db('save') }} </span>
                                        </a>
                                    </div>
                                </td>
                            @endcanany
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
        <h2 class="font-semibold mb-0 text-[18px] ">{{ __db('drivers') }} ({{ $delegation->drivers->count() }})</h2>
        @canany(['assign_accommodations', 'hotel_assign_accommodations'])
            <div class="items-center gap-3 ">
                <select id="hotelDriver" name="hotelDriver" class="select2 p-3 rounded-lg w-[300px] text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                    <option selected value="">{{ __db('select_hotel') }}</option>
                    @foreach ($hotels as $hot)
                        <option value="{{ $hot->id }}">{{ $hot->hotel_name }}</option>
                    @endforeach
                </select>
            </div>
        @endcanany
    </div>

    <div id="HotelInfoDriver" style="display: none;">
        <div class="bg-white p-4 w-auto rounded-lg border border-neutral-200 mt-3">
            <h4 class="font-semibold mb-3">{{ __db('room_details') }}</h4>
            <table class="min-w-full text-sm border border-neutral-300 rounded-lg">
                <thead class="bg-neutral-100">
                    <tr>
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
                    <tr class="text-[9px]">
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('sl_no') }}</th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('military_number') }}</th>
                        
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('name') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('mobile') }}</th>
                        
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('hotel') }}</th>
                        <th scope="col" class="p-2 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('room_type') }}</th>

                        <th scope="col" class="p-2 w-[100px] !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('room_number') }}</th>

                        
                        @canany(['assign_accommodations', 'hotel_assign_accommodations'])
                            <th scope="col" class="p-2 w-[60px] !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('action') }} </th>
                        @endcanany
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($delegation->drivers as $keyDriver => $rowDriver)
                        @php
                            $idDriver = $rowDriver->id ?? uniqid();
                            
                            $roomDriver = $rowDriver->currentRoomAssignment ?? null;
                            
                        @endphp

                        <tr  data-id="{{ $rowDriver->id }}" class="driver-row text-[11px] align-[middle] @if($roomDriver) bg-[#acf3bc] @endif" >
                            @canany(['assign_accommodations', 'hotel_assign_accommodations'])
                                <td class="px-1 py-2 border border-gray-200">
                                        <input type="checkbox" class="assign-hotel-checkbox-driver" data-driver-id="{{ $rowDriver->id }}" class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded">
                                </td>
                            @endcanany
                            <td class="px-1 py-2 border border-gray-200">{{ $keyDriver + 1 }}</td>
                            <td class="px-1 border border-gray-200 py-3">
                                <div class="block">{{ $rowDriver->name_en ?? ($rowDriver->name_ar ?? '-') }} {{ $rowDriver->title?->value }} </div>
                            </td>
                            <td class="px-1 border border-gray-200 py-3">
                                {{ $rowDriver->military_number ?? '-' }}
                            </td>
                            <td class="px-1 border border-gray-200 py-3">
                                {{ $rowDriver->phone_number ?? '-' }}
                            </td>
                           
                            <td class="px-1 border border-gray-200 py-3">
                                <span class="hotel_name_driver">{{ $roomDriver?->hotel?->hotel_name ?? '' }}</span>
                                <input type="hidden" name="hotel_id_driver" id="hotel_id_driver{{ $rowDriver->id }}" class="hotel-id-input-driver" data-driver-id="{{ $rowDriver->id }}" value="{{ $roomDriver?->hotel_id ?? '' }}">
                            </td>
                            <td class="px-1 border border-gray-200 py-3">

                                @php 
                                    $optionsDriver = '';
                                    if($roomDriver){
                                        $hotelidDriver = $roomDriver->hotel_id;
                                        $roomTypesDriver = App\Models\AccommodationRoom::with('roomType')->where('accommodation_id', $hotelidDriver)->get();
                                        foreach($roomTypesDriver as $roomTypeDriver){
                                            $optionsDriver .= '<option value="'.$roomTypeDriver->id.'" '.($roomTypeDriver->id == $roomDriver->room_type_id ? 'selected' : '').'>'.$roomTypeDriver->roomType?->value.'</option>';
                                        }
                                    }
                                @endphp

                                <select name="room_type_driver" id="room_type_driver" class="room-type-dropdown-driver p-1 rounded-lg w-full text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                    <option value="">{{ __db('select') }}</option>
                                    {!! $optionsDriver !!}
                                </select>
                            </td>
                            
                            <td class="px-1 border border-gray-200 py-3">
                                <input type="text" name="room_number_driver" id="room_number_driver" class="room-number-input-driver w-full p-1 rounded-lg text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ $roomDriver?->room_number ?? '' }}">
                            </td>
                            
                            @canany(['assign_accommodations', 'hotel_assign_accommodations'])
                                <td class="px-1 py-3 border border-gray-200">
                                    <div class="flex items-center gap-5">
                                        <a href="#" id="add-attachment-btn" class="save-room-assignment-driver text-xs !bg-[#B68A35] w-xs text-center text-white rounded-lg py-1 px-3">
                                            <span>{{ __db('save') }} </span>
                                        </a>
                                    </div>
                                </td>
                            @endcanany
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

</div>

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        $(document).on('change', '#hotelDelegate', function() {
            let hotelId = this.value;
            let url = "{{ route('accommodation.rooms', ':id') }}";
            url = url.replace(':id', hotelId);

            $.get(url, function (data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(r => {
                        let available = r.total_rooms - r.assigned_rooms;
                        html += `
                            <tr>
                                <td class="p-2 border">${r.room_type?.value ?? '-'}</td>
                                <td class="p-2 border text-center">${r.total_rooms}</td>
                                <td class="p-2 border text-center">${r.assigned_rooms}</td>
                                <td class="p-2 border text-center font-semibold">${available}</td>
                            </tr>
                        `;
                    });
                } else {
                    html = `<tr><td colspan="4" class="p-2 border text-center">{{ __db('no_rooms_found') }}</td></tr>`;
                }

                $('#roomDetails').html(html);
                $('#HotelInfo').show();
            });
        });

        $('.assign-hotel-checkbox').on('click', function() {
            let hotelId = $('#hotelDelegate').val();
            let Hotelname = $('#hotelDelegate option:selected').text();

            if (!hotelId) {
                this.checked = false;
                toastr.error('{{ __db("please_select_hotel") }}');
                return;
            }

            let row = $(this).closest('tr');
            let delegateId = row.data('id');
            let dropdown = row.find('.room-type-dropdown');
            let hotel_name = row.find('.hotel_name');

            if (this.checked) {
                let url = "{{ route('accommodation.rooms', ':id') }}";
                url = url.replace(':id', hotelId);

                $.get(url, function (data) {
                    dropdown.empty().append('<option value="">{{ __db("select") }}</option>');
                    data.forEach(function(room) {
                        let available = room.total_rooms - room.assigned_rooms;
                        if (available > 0) {
                            dropdown.append('<option value="'+room.id+'">'+room.room_type?.value+'</option>');
                        }
                    });
                });

                hotel_name.text(Hotelname);
                $('#hotel_id' + delegateId).val(hotelId);
            } else {
                dropdown.empty().append('<option value="">{{ __db("select") }}</option>');
            }
        });

        $('.save-room-assignment').on('click', function(e) {
            e.preventDefault();
            let row = $(this).closest('tr');
            let checkboxDel = row.find('input[type="checkbox"]');
            let delegateId = row.data('id');
            let hotelId = $('#hotel_id' + delegateId).val();
            let roomTypeId = row.find('.room-type-dropdown').val();
            let roomNumber = row.find('.room-number-input').val();

            if (!hotelId || !roomTypeId || !roomNumber) {
                toastr.error('{{ __db("please_select_room_details") }}');
                return;
            }

            $.post("{{ route('accommodation.assign-rooms') }}", {
                _token: '{{ csrf_token() }}',
                assignable_id: delegateId,
                assignable_type: 'Delegate',
                hotel_id: hotelId,
                room_type_id: roomTypeId,
                room_number: roomNumber,
                delegation_id : {{ $delegation->id }}
            }, function(res) {
                if(res.success) {
                    row.css('background-color', '#acf3bc');
                    toastr.success('{{ __db("room_assigned") }}');
                    // row.find('.room-type-dropdown').val('');
                    // row.find('.room-number-input').val('');
                } else {
                    toastr.success('{{ __db("room_already_assigned") }}');
                }
                checkboxDel.prop('checked', false);
            });
        });

        // Escort Section
        $(document).on('change', '#hotelEscort', function() {
            let hotelId = this.value;
            let url = "{{ route('accommodation.rooms', ':id') }}";
            url = url.replace(':id', hotelId);

            $.get(url, function (data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(r => {
                        let available = r.total_rooms - r.assigned_rooms;
                        html += `
                            <tr>
                                <td class="p-2 border">${r.room_type?.value ?? '-'}</td>
                                <td class="p-2 border text-center">${r.total_rooms}</td>
                                <td class="p-2 border text-center">${r.assigned_rooms}</td>
                                <td class="p-2 border text-center font-semibold">${available}</td>
                            </tr>
                        `;
                    });
                } else {
                    html = `<tr><td colspan="4" class="p-2 border text-center">{{ __db('no_rooms_found') }}</td></tr>`;
                }

                $('#roomDetailsEscort').html(html);
                $('#HotelInfoEscort').show();
            });
        });

        $('.assign-hotel-checkbox-escort').on('click', function() {
            let hotelIdEscort = $('#hotelEscort').val();
            let HotelnameEscort = $('#hotelEscort option:selected').text();

            if (!hotelIdEscort) {
                this.checked = false;
                toastr.error('{{ __db("please_select_hotel") }}');
                return;
            }

            let rowEscort = $(this).closest('tr');
            let escortId = rowEscort.data('id');
            let dropdownEscort = rowEscort.find('.room-type-dropdown-escort');
            let hotel_nameEscort = rowEscort.find('.hotel_name_escort');

            if (this.checked) {
                let url = "{{ route('accommodation.rooms', ':id') }}";
                url = url.replace(':id', hotelIdEscort);

                $.get(url, function (data) {
                    dropdownEscort.empty().append('<option value="">{{ __db("select") }}</option>');
                    data.forEach(function(room) {
                        let availableEscort = room.total_rooms - room.assigned_rooms;
                        if (availableEscort > 0) {
                            dropdownEscort.append('<option value="'+room.id+'">'+room.room_type?.value+'</option>');
                        }
                    });
                });

                hotel_nameEscort.text(HotelnameEscort);
                $('#hotel_id_escort' + escortId).val(hotelIdEscort);
            } else {
                dropdownEscort.empty().append('<option value="">{{ __db("select") }}</option>');
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

            if (!hotelIdEscort || !roomTypeIdEscort || !roomNumberEscort) {
                toastr.error('{{ __db("please_select_room_details") }}');
                return;
            }

            $.post("{{ route('accommodation.assign-rooms') }}", {
                _token: '{{ csrf_token() }}',
                assignable_id: idEscort,
                assignable_type: 'Escort',
                hotel_id: hotelIdEscort,
                room_type_id: roomTypeIdEscort,
                room_number: roomNumberEscort,
                delegation_id : {{ $delegation->id }}
            }, function(res) {
                if(res.success) {
                    escortrow.css('background-color', '#acf3bc');
                    toastr.success('{{ __db("room_assigned") }}');
                    // row.find('.room-type-dropdown').val('');
                    // row.find('.room-number-input').val('');
                } else {
                    toastr.success('{{ __db("room_already_assigned") }}');
                }
                checkboxEscort.prop('checked', false);
            });
        });

        // Drivers Section
        $(document).on('change', '#hotelDriver', function() {
            let hotelId = this.value;
            let url = "{{ route('accommodation.rooms', ':id') }}";
            url = url.replace(':id', hotelId);

            $.get(url, function (data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(r => {
                        let available = r.total_rooms - r.assigned_rooms;
                        html += `
                            <tr>
                                <td class="p-2 border">${r.room_type?.value ?? '-'}</td>
                                <td class="p-2 border text-center">${r.total_rooms}</td>
                                <td class="p-2 border text-center">${r.assigned_rooms}</td>
                                <td class="p-2 border text-center font-semibold">${available}</td>
                            </tr>
                        `;
                    });
                } else {
                    html = `<tr><td colspan="4" class="p-2 border text-center">{{ __db('no_rooms_found') }}</td></tr>`;
                }

                $('#roomDetailsDriver').html(html);
                $('#HotelInfoDriver').show();
            });
        });

        $('.assign-hotel-checkbox-driver').on('click', function() {
            let hotelIdDriver = $('#hotelDriver').val();
            let HotelnameDriver = $('#hotelDriver option:selected').text();

            if (!hotelIdDriver) {
                this.checked = false;
                toastr.error('{{ __db("please_select_hotel") }}');
                return;
            }

            let rowDriver = $(this).closest('tr');
            let driverId = rowDriver.data('id');
            let dropdownDriver = rowDriver.find('.room-type-dropdown-driver');
            let hotel_nameDriver = rowDriver.find('.hotel_name_driver');

            if (this.checked) {
                let url = "{{ route('accommodation.rooms', ':id') }}";
                url = url.replace(':id', hotelIdDriver);

                $.get(url, function (data) {
                    dropdownDriver.empty().append('<option value="">{{ __db("select") }}</option>');
                    data.forEach(function(room) {
                        let availableDriver = room.total_rooms - room.assigned_rooms;
                        if (availableDriver > 0) {
                            dropdownDriver.append('<option value="'+room.id+'">'+room.room_type?.value+'</option>');
                        }
                    });
                });

                hotel_nameDriver.text(HotelnameDriver);
                $('#hotel_id_driver' + driverId).val(hotelIdDriver);
            } else {
                dropdownDriver.empty().append('<option value="">{{ __db("select") }}</option>');
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

            if (!hotelIdDriver || !roomTypeIdDriver || !roomNumberDriver) {
                toastr.error('{{ __db("please_select_room_details") }}');
                return;
            }

            $.post("{{ route('accommodation.assign-rooms') }}", {
                _token: '{{ csrf_token() }}',
                assignable_id: idDriver,
                assignable_type: 'Driver',
                hotel_id: hotelIdDriver,
                room_type_id: roomTypeIdDriver,
                room_number: roomNumberDriver,
                delegation_id : {{ $delegation->id }}
            }, function(res) {
                if(res.success) {
                    driverrow.css('background-color', '#acf3bc');
                    toastr.success('{{ __db("room_assigned") }}');
                    // row.find('.room-type-dropdown').val('');
                    // row.find('.room-number-input').val('');
                } else {
                    toastr.success('{{ __db("room_already_assigned") }}');
                }
                checkboxDriver.prop('checked', false);
            });
        });
    });

</script>
@endsection