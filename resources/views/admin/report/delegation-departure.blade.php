@extends('layouts.admin_account', ['title' => __db('reports')])

@section('content')
<style>
    .select2-container--default .select2-selection--multiple {
        min-height: 2rem !important;
        /* height: 40px !important; */
        padding: 0.2rem 0.75rem;
    }
</style>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('departures_report') }}</h2>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            
             <form class="w-[75%] me-4"  method="GET"> 
                <div class="flex relative">
                    
                    <div class="flex flex-row w-[70%] gap-4">
                        <div class="w-[50%]">
                            <input type="text" class="block w-full text-secondary-light text-sm !border-[#d1d5db] rounded-lg date-range" id="date_range" name="date_range" placeholder="DD-MM-YYYY - DD-MM-YYYY" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off" value="{{ request('date_range') }}">
                        </div>

                        <div class="w-[50%]">
                            <select name="airport[]" multiple data-placeholder="{{ __db('select') }} {{ __db('airport') }}" class="select2 rounded-lg border border-gray-300 text-sm w-full">
                                <option value="">{{ __db('select') }} {{ __db('airport') }}</option>
                                @foreach (getDropDown('airports')->options as $option)
                                    <option value="{{ $option->id }}" @if (in_array($option->id, request('airport', []))) selected @endif>
                                        {{ $option->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="w-[30%]"> 
                        <button type="submit" class="!text-[#5D471D] mr-2  end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>

                        <a href="{{ route('report.delegation-departures') }}"
                            class=" end-[80px]  bottom-[3px] mr-2 border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">{{ __db('reset') }}</a>
                    </div>
                        
                </div>
            </form>

            <div class="flex gap-3 ms-auto">
                @directCanany(['export_departures_report'])
                    <form action="{{ route('delegation-departures.bulk-exportPdf') }}" method="POST" style="display:inline;">
                        @csrf
                        @foreach (request()->except('limit', 'page') as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $subKey => $subValue)
                                    <input type="hidden" name="{{ $key }}[{{ $subKey }}]"
                                        value="{{ $subValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="!text-[#5D471D]  !bg-[#E6D7A2] hover:bg-yellow-400 rounded-lg py-2 px-3">
                            {{ __db('export_pdf') }}
                        </button>
                    </form>
                @enddirectCanany
                <x-back-btn class="" back-url="{{ route('reports.index') }}" />
            </div>
        </div>

        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6" dir="ltr"  style="font-size: 12px">
            @foreach($formattedGroups as $group)
                @php
                    $delegation = $group->delegation;
                    $delegates = $group->delegates;
                    $assignedHotels = [];
                    $escortAccHtml = $escortContactHtml = $escortDetailsHtml = '';
                @endphp

                 <table style="width:100%; border-collapse: collapse; margin-bottom: 5px;">
                    <tbody>
                        <tr>
                            <td style="width:40%; padding: 3px; border: 0px;">
                                <div style="">
                                    {{ $delegation?->country?->name ?? '-' }}                                    
                                    <strong> : {{ __db('country') }}</strong>
                                </div>
                            
                            </td>
                            <td style="width:15%; padding: 3px; border: 0px;">
                                
                            </td>

                            <td style="width:55%; padding: 3px; border: 0px;text-align: right;">
                                <div style="">
                                    {{ $delegation->invitationFrom?->value ?? '-' }}
                                    <strong> : {{ __db('invitation_from') }}</strong>
                                </div>
                                
                            </td>
                        </tr>
                        <tr>
                            <td style="width:40%; padding: 6px; border: 0px;">
                            
                                <div style="margin-bottom: 5px;">
                                    {{ $delegation?->note2 ?? '-' }}
                                    <strong> : {{ __db('note_2') }}</strong>
                                </div>
                            </td>
                            <td style="width:15%; padding: 6px; border: 0px;">
                                
                            </td>

                            <td style="width:55%; padding: 6px; border: 0px;text-align: right;">
                            
                                <div style="margin-bottom: 5px; display: flex; justify-content: flex-end;">
                                    <span>{{ $delegation?->note1 ?? '-' }}</span>
                                    <strong style="width: 22%;"> : {{ __db('note_1') }}</strong>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table style="width:100%; border-collapse: collapse; margin-bottom: 10px;">
                    <tbody>
                        @foreach ($delegation?->escorts as $key => $escort)
                            @php
                                $roomEscort = $escort->currentRoomAssignment ?? null;
                                $assignedHotels[] = $roomEscort?->hotel_id ?? null;
                            @endphp

                            <tr>
                                <td style="padding: 6px; border: 0px;">
                                    {{ $roomEscort?->room_number }} - {{ $roomEscort?->hotel?->hotel_name }} <strong> : {{ __db('accommodation') }}</strong>
                                </td>
                                <td style="padding: 6px; border: 0px;">
                                    {{ $escort?->phone_number }} <strong> : {{ __db('mobile') }} </strong>
                                </td>
                                <td style="padding: 6px; border: 0px;text-align: right;">
                                    {{ $escort?->internalRanking?->value }} {{ $escort?->name }} - {{ $escort?->military_number }}  @if($key == 0) <strong> : {{ __db('escort') }}</strong> @else <strong style="margin-right: 45px;"></strong> @endif
                                        
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; border-bottom: 0;">
                    <thead>
                        <tr style="background-color: #d9d9d9;">
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('room') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('room_type') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('hotel') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('time') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('date') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('flight_number') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('flight_name') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('airport') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('position') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegations') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center;">
                         @php
                        $separator = (getActiveLanguage() === 'ar') ? ' / ' : ' . ';
                    @endphp
                        @forelse ($delegates as $key => $delegate)
                            @php
                                $delegateRoom = $delegate->currentRoomAssignment ?? null;
                                $assignedHotels[] = $delegateRoom?->hotel_id ?? null;
                                
                                $relation = '';
                                if($delegate->relationship){
                                    $relation = $delegate->relationship?->value .' '. __db('of') .' '. $delegate->parent?->getTranslation('name');
                                }
                                $departure = $delegate->delegateTransports->where('type', 'departure')->first();
                            @endphp
                            <tr>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $delegateRoom ? $delegateRoom?->room_number : '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $delegateRoom?->roomType?->roomType?->value }}</td>
                                <td style="padding: 8px; border: 2px solid #000;">
                                    {{ $delegateRoom?->hotel?->hotel_name ?? __db('not_required')}}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; @if($delegate->team_head === true) color: red; @endif">
                                    {{ $departure?->date_time ? date('H:i', strtotime($departure?->date_time)) : '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000;">
                                    {{ $departure?->date_time ? date('d-m-Y', strtotime($departure?->date_time)) : '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $departure?->flight_no ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $departure?->flight_name ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $departure?->airport?->value ?? ucwords($departure?->mode) }}</td>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $delegate?->getTranslation('designation') ?? $relation }}</td>
                                <td style="padding: 8px; border: 2px solid #000; @if($delegate->team_head === true) color: red; @endif">
                                    <strong>
                                        {{ $delegate->getTranslation('title').''.$separator.' '.$delegate->getTranslation('name') }}
                                    </strong>
                                </td>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $key + 1 }}</td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td colspan="9" style="padding: 8px; border: 1px solid #000; text-center">
                                    {{ __db('no_record_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($delegation->drivers->isNotEmpty())
                    <div style="display: flex; justify-content: space-between; align-items: end; margin-bottom: 20px; gap: 20px; border: 2px solid #000; border-top: 0; padding: 8px;">
                        <table style="width:100%; border-collapse:collapse;">
                            @foreach($delegation?->drivers as $index => $driver)
                                <tr>
                                    <td style="padding:6px;text-align:right;width: 20%;">
                                        <span >{{ $driver->car_number }}</span>
                                        <strong> : {{ __db('car_number') }}</strong>
                                    </td>
                                    <td style="padding:6px;text-align:right;width: 20%;">
                                        <span >{{ $driver->car_type }}</span>
                                        <strong> : {{ __db('car_type') }}</strong>
                                    </td>
                                    <td style="padding:6px;text-align:right;width: 20%;">
                                        <span >{{ $driver->phone_number }}</span>
                                        <strong> : {{ __db('mobile') }}</strong>
                                    </td>
                                    <td style="padding:6px;text-align:right;width: 40%;">
                                        <span >{{ $driver?->getTranslation('name') }} {{ $driver?->getTranslation('title') }} - {{ $driver?->military_number }}</span>
                                        <strong> : {{ __db('driver') }}{{ $index+1 }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @else
                    <div style="margin-bottom: 20px; ">
                       
                    </div>
                @endif
            
            @endforeach
        </div>
    </div>
@endsection