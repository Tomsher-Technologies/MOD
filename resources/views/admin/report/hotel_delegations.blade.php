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
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('hotel_delegations_report') }}</h2>
            <div class="flex gap-3 ms-auto">
                @directCanany(['export_hotel_delegations'])
                    <form action="{{ route('hotels-delegations.bulk-exportPdf') }}" method="POST" style="display:inline;">
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
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">

            <form class="w-full me-4" method="GET">
                <div class="flex relative">

                    <div class="flex flex-row w-[25%] gap-4">
                        
                        <div class="w-[100%]">
                            <select name="hotel[]" multiple id="hotel"
                                data-placeholder="{{ __db('select') }} {{ __db('hotel') }}"
                                class="select2 w-full rounded-lg border border-gray-300 text-sm">
                                <option value="">{{ __db('select') }} {{ __db('hotel') }}</option>
                                @foreach ($hotels as $hotel)
                                    <option value="{{ $hotel->id }}"
                                        {{ is_array(request('hotel')) && in_array($hotel->id, request('hotel')) ? 'selected' : '' }}>
                                        {{ $hotel->hotel_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                       
                    </div>


                    <div class="w-[30%]">
                        <button type="submit"
                            class="!text-[#5D471D] mr-2  end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>

                        <a href="{{ route('report.hotels-delegations') }}"
                            class=" end-[80px]  bottom-[3px] mr-2 border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">{{ __db('reset') }}</a>
                    </div>

                </div>
            </form>
        </div>

        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6" dir="ltr"
            style="font-size: 12px">

            @foreach($hotelsData as $hotel)

                @php
                    $delegations = $hotel->rooms
                                        ->flatMap(fn($room) => $room->roomAssignments)
                                        ->groupBy('delegation_id');
                    $serial = 1;
                @endphp

                @if($delegations->count() > 0)
                    <table style="width: 100%; margin-top: 20px; font-weight: bold; font-size: 16px; border-collapse: collapse;">
                        <tr>
                            <td style="text-align: right; white-space: nowrap;">
                                {{ $hotel->hotel_name }} : {{ __('Hotel') }}
                            </td>
                        </tr>
                    </table>
         
                    @foreach($delegations as $delegationId => $assignments)
                        @php
                            $delegation = $assignments->first()->delegation;
                        @endphp

                        <table style="border: 2px solid black; border-collapse: collapse; width: 100%; margin-bottom: 30px;">
                            <thead>
                                <tr>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('invitation_from') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('room_number') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('room_type') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('position') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('delegate') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('country') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('sl_no') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tbody>
                                    @foreach($assignments as $index => $assignment)
                                        <tr>
                                            
                                            @if($index == 0)
                                                <td style="border:2px solid black; padding:5px; text-align:center;" rowspan="{{ count($assignments) }}">
                                                    {{ $delegation->invitationFrom?->value ?? '-' }}
                                                </td>
                                            @endif

                                            <td style="border:2px solid black; padding:5px; text-align:center;">{{ $assignment->room_number }}</td>

                                            <td style="border:2px solid black; padding:5px; text-align:center;">
                                                {{ $assignment->roomType?->roomType?->value ?? '' }}
                                            </td>

                                            <td style="border:2px solid black; padding:5px; text-align:center;@if($assignment->assignable?->team_head === true) color: red; @endif">
                                                {{ $assignment->assignable?->getTranslation('designation') ?? '' }}
                                            </td>

                                            <td style="border:2px solid black; padding:5px; text-align:center; @if($assignment->assignable?->team_head === true) color: red; @endif">
                                                {{ $assignment->assignable?->getTranslation('title') .' '.$assignment->assignable?->getTranslation('name') }}
                                            </td>

                                            @if($index == 0)
                                                <td style="border:2px solid black; padding:5px; text-align:center;" rowspan="{{ count($assignments) }}">
                                                    {{ $delegation->country?->name ?? '' }}
                                                </td>
                                                <td style="border:2px solid black; padding:5px; text-align:center;" rowspan="{{ count($assignments) }}">
                                                    {{ $serial }}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach

                                    @foreach ($delegation->escorts as $key => $escort)
                                            @php
                                                $roomEscort = $escort->currentRoomAssignment ?? null;
                                            @endphp
                                            <tr>
                                                <td style="padding: 6px; border: 0px;" colspan="2">
                                                    {{ $roomEscort?->room_number }} - {{ $roomEscort?->roomType?->roomType?->value ?? ''  }} - {{ $roomEscort?->hotel?->hotel_name }} 
                                                    <strong> : {{ __db('accommodation') }}</strong>
                                                </td>
                                                <td style="padding: 6px; border: 0px;" colspan="2">
                                                    {{ $escort?->phone_number }} <strong> : {{ __db('mobile') }} </strong>
                                                </td>
                                                <td style="padding: 6px; border: 0px;text-align: right;" colspan="3">
                                                    <span style="{{ $key != 0 ? 'margin-right: 40px;' : '' }}">{{ $escort?->internalRanking?->value }} {{ $escort?->name }} - {{ $escort?->military_number }}  <span>
                                                    @if($key == 0) <strong> : {{ __db('escort') }}</strong> @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                </tbody>


                            </tbody>
                        </table>

                        @php
                            $serial++;
                        @endphp
                    @endforeach
                @endif
                
            @endforeach
        </div>
    </div>
@endsection
